<?php

class Person extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('person_model');
        $this->load->model('nation_model');
        $this->load->model('bundesland_model');
        $this->load->helper("form");
    }

    public function index() {
        $this->_data['title'] = 'Personendaten';
        
        //load nationen
        if($this->nation_model->getNationen())
        {
            if($this->nation_model->result->error == 0)
            {
                foreach($this->nation_model->result->retval as $n)
                {
                    $this->_data["nationen"][$n->nation_code] = $n->kurztext;
                }
                
            }
        }
           
        //load bundeslaender
        if($this->bundesland_model->getBundeslaender())
        {
            if($this->bundesland_model->result->error == 0)
            {
                foreach($this->bundesland_model->result->retval as $b)
                {
                    $this->_data["bundeslaender"][$b->bundesland_code] = $b->bezeichnung;
                }
            }
        }
        
        $this->_data["plz"] = array();

        $this->load->view('person', $this->_data);
    }

}
