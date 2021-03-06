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
	
	private $_personSessionName = 'Person.getPerson';
    private $_person_id;

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
        $this->_loadConfigs(array('aufnahme', "message"));
		$this->output->enable_profiler($this->config->item('profiler'));
		
		// Load return message helper
		$this->load->helper('message');
		
		//$this->load->helper('url');
		$this->load->library('session');
		/*$this->load->model("phrase_model", "PhraseModel");
		$this->load->model("sprache_model", "SpracheModel");
		$this->load->model("message_model", "MessageModel");*/
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
	    $this->_loadModels(array("PhraseModel"=>"phrase_model"));

		$language = null;
		
		if (is_null($this->input->get('language')))
		{
			if (is_null($this->session->language))
			{
                if (((is_null($this->session->phrasen)) || (empty($this->session->phrasen))) ||
                    (!$this->config->item('store_phrases_in_session')))
                {
                    $this->_getPhrasen(ucfirst($this->config->item('default_language')));
                }
				$language = $this->config->item('default_language');
			}
			else
			{
                if (((is_null($this->session->phrasen)) || (empty($this->session->phrasen))) ||
                    (!$this->config->item('store_phrases_in_session')))
                {
                    $this->_getPhrasen(ucfirst($this->session->language));
                }
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
		$logged = false;
		
		if (isset($this->session->{$this->_personSessionName}))
		{
			$person = $this->session->{$this->_personSessionName};
			if (hasData($person))
			{
				if (isset($person->retval->person_id) && is_numeric($person->retval->person_id))
				{
					$logged = true;
				}
			}
		}
		
		if ($logged === false)
		{
			redirect("/Registration");
		}
	}

    /**
     * @param $languages array
     */
	protected function _loadCiLanguages($languages)
    {
        if((is_array($languages)) && (!empty($languages)))
        {
            foreach($languages as $lang)
            {
                $this->lang->load($lang, $this->get_language());
            }
        }
    }

    /**
     * @param $models array
     */
    protected function _loadModels($models)
    {
        if((is_array($models)) && (!empty($models)))
        {
            foreach($models as $key=>$model)
            {
                $this->load->model($model, $key);
            }
        }
    }

    /**
     * @param $libraries array
     */
    protected function _loadLibraries($libraries)
    {
        if((is_array($libraries)) && (!empty($libraries)))
        {
            foreach($libraries as $key=>$library)
            {
                $this->load->library($library);
            }
        }
    }

    /**
     * @param $helpers array
     */
    protected function _loadHelpers($helpers)
    {
        if((is_array($helpers)) && (!empty($helpers)))
        {
            foreach($helpers as $key=>$helper)
            {
                $this->load->helper($helper);
            }
        }
    }

    protected function _loadConfigs($configs)
    {
        if((is_array($configs)) && (!empty($configs)))
        {
            foreach($configs as $key=>$config)
            {
                $this->load->config($config);
            }
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
	function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null)
    {
        $this->_loadModels(array("PhraseModel"=>"phrase_model"));

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
        $this->_loadModels(array("SpracheModel"=>"sprache_model"));
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
        if (isset($this->session->{'Person.getPerson'}))
        {
            $person = $this->session->{'Person.getPerson'};
            if (hasData($person))
            {
                if (isset($person->retval->person_id) && is_numeric($person->retval->person_id))
                {
                    $this->_person_id = $person->retval->person_id;
                }
            }
        }

        $this->_loadModels(array("MessageModel"=>"message_model"));
		if(isset($this->_person_id))
		{
			$this->_data["messages"] = $this->_getMessages($this->_person_id);
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
