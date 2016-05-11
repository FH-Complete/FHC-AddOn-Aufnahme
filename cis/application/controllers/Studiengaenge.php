<?php

class Studiengaenge extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('studiengang_model');
    }

    public function index() {
        $this->_data['title'] = 'Overview';
        
        $this->studiengang_model->getAll();
        
        if(is_object($this->studiengang_model->result) && $this->studiengang_model->result->success)
        {
            $this->_data["studiengaenge"] = $this->studiengang_model->result->data;
        }
        else
        {
            //TODO could not load data
        }
        
        $this->load->view('studiengaenge', $this->_data);
    }
}
