<?php

class Bewerbung extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('person_model');
        $this->load->model('nation_model');
        $this->load->model('bundesland_model');
        $this->load->helper("form");
        
        //load nationen
        if($this->nation_model->getNationen())
        {
            if($this->nation_model->result->success)
            {
                foreach($this->nation_model->result->data as $n)
                {
                    $this->_data["nationen"][$n->nation_code] = $n->kurztext;
                }
                
            }
        }
           
        //load bundeslaender
        if($this->bundesland_model->getBundeslaender())
        {
            if($this->bundesland_model->result->success)
            {
                foreach($this->bundesland_model->result->data as $b)
                {
                    $this->_data["bundeslaender"][$b->bundesland_code] = $b->bezeichnung;
                }
            }
        }
        
        $this->_data["plz"] = array();
    }

    public function index() {
        $this->_data['title'] = 'Personendaten';
        $this->load->view('person', $this->_data);
    }
    
    public function studiengang()
    {
        var_dump($this->input->get);
        $this->load->view('person', $this->_data);
    }

}
