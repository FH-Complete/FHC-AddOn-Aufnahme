<?php
/**
 * ./cis/application/core/MY_Controller.php
 *
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $_data = array();

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->config('aufnahme');
		$this->config->load('message');
		$this->output->enable_profiler($this->config->item('profiler'));
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model("phrase_model", "PhraseModel");
		$this->load->model("sprache_model", "SpracheModel");
		$this->load->model("message_model", "MessageModel");
		//$this->load->spark('restclient/2.1.0');
		$this->_data['language'] = $this->get_language();
		$this->_getSprache($this->_data['language']);
	}

	/**
	 *
	 * @return unknown
	 */
	public function get_language()
	{
		$language = null;
		
		if (is_null($this->input->get('language')))
		{
			if (is_null($this->session->language))
			{
				$this->_getPhrasen(ucfirst($this->config->item('default_language')));
				$language = $this->config->item('default_language');
			}
			else
			{
				$this->_getPhrasen(ucfirst($this->session->language));
				$language =  $this->session->language;
			}
		}
		else
		{
			$this->session->language = $this->input->get('language');
			$this->_getPhrasen(ucfirst($this->session->language));
			$language = $this->input->get('language');
		}
		
		return $language;
	}

	/**
	 *
	 */
	public function checkLogin()
	{
		if (is_null($this->session->person_id))
		{
			redirect("/Registration");
		}
	}

	/**
	 *
	 * @param unknown $sprache
	 */
	protected function _loadLanguage($sprache)
	{
		if (((is_null($this->session->phrasen)) || (empty($this->session->phrasen))) ||
			(!$this->config->item('store_phrases_in_session')))
		{
			$this->_getPhrasen($sprache);
		}
	}

	private function _getPhrasen($language)
	{
		$this->PhraseModel->getPhrasen(ucfirst($language));
		if ($this->PhraseModel->isResultValid() == true)
		{
			$this->session->phrasen = $this->PhraseModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PhraseModel->getErrorMessage());
		}
	}

	/**
	 *
	 * @param unknown $phrase
	 * @param unknown $sprache
	 * @param unknown $oe_kurzbz      (optional)
	 * @param unknown $orgform_kurzbz (optional)
	 * @return unknown
	 */
	function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null) {
		if (isset($this->session->userdata()["phrasen"])) {
			$phrasen = $this->session->userdata()["phrasen"];
			if (is_array($phrasen)) {
				$text = "";
				$sprache = ucfirst($sprache);
				foreach ($phrasen as $p) 
				{	
					if($p->phrase == $phrase)
					{
						if (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz) && ($p->sprache == $sprache))
						{
							if ($this->config->item('display_phrase_name'))
								$text = $p->text . " <i>[$p->phrase]</i>";
							else
								$text = $p->text;
						}
						elseif (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
						{
							if ($this->config->item('display_phrase_name'))
								$text = $p->text . " <i>[$p->phrase]</i>";
							else
								$text = $p->text;
						}
						elseif (($p->orgeinheit_kurzbz == $this->config->item("root_oe")) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
						{
							if ($this->config->item('display_phrase_name'))
								$text = $p->text . " <i>[$p->phrase]</i>";
							else
								$text = $p->text;
						}
						elseif (($p->orgeinheit_kurzbz == null) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
						{
							if ($this->config->item('display_phrase_name'))
								$text = $p->text . " <i>[$p->phrase]</i>";
							else
								$text = $p->text;
						}
					}
				}

				if($text != "")
					return $text;
				
				if ($this->config->item('display_phrase_name'))
					return "<i>[$phrase]</i>";
			}
			else {
				return $phrasen;
			}
		}
		else {
			return "please load phrases first";
		}
	}

	protected function _setError($bool, $msg = null)
	{
		$this->_data["error"] = new stdClass();
		$this->_data["error"]->error = $bool;
		$this->_data["error"]->msg = $msg;
	}
	
	private function _getSprache($sprache)
	{
		if((is_null($this->session->sprache)) || (ucfirst($sprache) != $this->session->sprache->sprache))
		{
			$this->SpracheModel->getSprache(ucfirst($sprache));
			if ($this->SpracheModel->isResultValid() == true)
			{
				$this->session->sprache = $this->SpracheModel->result->retval[0];
			}
			else
			{
				$this->_setError(true, $this->SpracheModel->getErrorMessage());
			}
		}
	}
	
	protected function _getNumberOfUnreadMessages()
	{
		if(isset($this->session->userdata()["person_id"]))
		{
			$this->_data["messages"] = $this->_getMessages($this->session->userdata()["person_id"]);
			$numberOfUnreadMessages = 0;
			foreach($this->_data["messages"] as $msg)
			{
				if($msg->status == MSG_STATUS_UNREAD)
				{
					$numberOfUnreadMessages++;
				}
			}
			return $numberOfUnreadMessages;
		}
		return 0;
	}
	
	private function _getMessages($person_id)
	{
		$this->MessageModel->getMessagesByPersonId($person_id);
		if($this->MessageModel->isResultValid() === true)
		{
			return $this->MessageModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}
	
	protected function cmpStg($a, $b)
	{
		return strcmp ($a->bezeichnung, $b->bezeichnung);
	}
}
