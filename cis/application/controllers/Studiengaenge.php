<?php

class Studiengaenge extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('studiengang_model');
        $this->lang->load('studiengaenge', $this->get_language());
    }

    public function index() {
        $this->checkLogin();
        $this->_data['title'] = 'Overview';
        $this->_data['sprache'] = $this->get_language();
        
        $this->studiengang_model->getAll();
        
        if($this->studiengang_model->result->error == 0)
        {
            $this->_data["studiengaenge"] = $this->studiengang_model->result->retval;
        }
        else
        {
            //TODO could not load data
        }
        $this->load->view('studiengaenge', $this->_data);
    }
}
