<?php

class Requirements extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('requirements', $this->get_language());
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->model('studiengang_model', "StudiengangModel");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        //load studiengang
        $this->_loadStudiengang(227);
        
        $this->load->view('requirements', $this->_data);
    }
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["preinteressent"]->studiengang_kz;
        }
        if($this->StudiengangModel->getStudiengang($stgkz))
        {
            if(($this->StudiengangModel->result->error == 0) && (count($this->StudiengangModel->result->retval) == 1))
            {
                $this->_data["studiengang"] = $this->StudiengangModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
            }
        }
    }
}
