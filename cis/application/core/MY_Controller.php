<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $_data = array();

    public function __construct() {
        parent::__construct();
        //$this->load->config('aufnahme.dist');
        //$this->load->config('aufnahme', FALSE, TRUE);
        $this->load->config('aufnahme');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model("phrase_model", "PhraseModel");
        //$this->load->spark('restclient/2.1.0');
        $this->_data['language'] = $this->get_language();
    }

    public function get_language() {
        if (is_null($this->input->get('language')))
	{
            if (is_null($this->session->sprache))
	    {
		$this->_getPhrasen(ucfirst($this->config->item('default_language')));
                return $this->config->item('default_language');
            }
	    else
	    {
		$this->_getPhrasen(ucfirst($this->session->language));
                return $this->session->language;
	    }
        }
        else
	{
            $this->session->language = $this->input->get('language');
	    $this->_getPhrasen(ucfirst($this->session->language));
            return $this->input->get('language');
        }
    }

    public function checkLogin()
    {
        if(is_null($this->session->person_id))
        {
            redirect("/Login");
        }
    }

    protected function _loadLanguage($sprache)
    {
	if(((is_null($this->session->phrasen)) || (empty($this->session->phrasen))) || (!$this->config->item('store_phrases_in_session')))
        {
            $this->_getPhrasen($sprache);
        }
    }

    private function _getPhrasen($language)
    {
	$this->PhraseModel->getPhrasen(ucfirst($language));
	if($this->PhraseModel->isResultValid() == true)
	{
	    //TODO Phrasen loaded
	    $this->session->phrasen = $this->PhraseModel->result->retval;
	}
	else
	{
	    //TODO
	    echo $this->PhraseModel->getErrorMessage();
	    var_dump($this->PhraseModel->getErrorMessage());
	}
    }

    protected function getPhrase($phrase, $sprache, $oe_kurzbz="", $orgform_kurzbz="")
    {
	if(isset($this->session->userdata()["phrasen"]))
	{
	    $phrasen = $this->session->userdata()["phrasen"];
	    foreach($phrasen as $p)
	    {
		if(($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz))
		{
		    if ($this->config->item('display_phrase_name'))
		    {
			return $p->phrase;
		    }
		    else
		    {
			return $p->text;
		    }
		}
	    }

	    foreach($phrasen as $p)
	    {
		if(($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz))
		{
		    if ($this->config->item('display_phrase_name'))
		    {
			return $p->phrase;
		    }
		    else
		    {
			return $p->text;
		    }
		}
	    }

	    foreach($phrasen as $p)
	    {
		if(($p->phrase == $phrase))
		{
		    if ($this->config->item('display_phrase_name'))
		    {
			return $p->phrase;
		    }
		    else
		    {
			return $p->text;
		    }
		}
	    }
	}
	else
	{
	    return "please load phrases first";
	}
    }

    protected function _setError($bool, $msg)
    {
	$this->_data["error"] = new stdClass();
	$this->_data["error"]->error = $bool;
	$this->_data["error"]->msg = $msg;
    }
}
