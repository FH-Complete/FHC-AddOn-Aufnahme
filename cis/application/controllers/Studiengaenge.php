<?php

class Studiengaenge extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('studiengang_model');
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisationsform_model', 'OrgformModel');
        $this->lang->load('studiengaenge', $this->get_language());
    }

    public function index() {
        $this->checkLogin();
        $this->_data['title'] = 'Overview';
        $this->_data['sprache'] = $this->get_language();
        
        $this->StudiensemesterModel->getNextStudiensemester("WS");
        $this->OrgformModel->getAll();
        
        if($this->OrgformModel->result->error == 0)
        {
            $this->_data["orgform"] = $this->OrgformModel->result->retval;
        }
        else
        {
            //TODO error while loading orgform
        }
        
        if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
        {
            $this->_data["studiensemester"] = $this->StudiensemesterModel->result->retval[0];
            $this->studiengang_model->getAll();
        
            if($this->studiengang_model->result->error == 0)
            {
                foreach($this->studiengang_model->result->retval as $key=>$studiengang)
                {
                    $this->StudienplanModel->getStudienplaeneFromSem(array(
                                "studiengang_kz"=>$studiengang->studiengang_kz,
                                "studiensemester_kurzbz"=>$this->_data["studiensemester"]->studiensemester_kurzbz,
                                "ausbildungssemester"=>1
                        ));
                    
                    if($this->StudienplanModel->result->error == 0)
                    {
                        $studiengang->studienplaene = $this->StudienplanModel->result->retval;
                    }
                }
                $this->_data["studiengaenge"] = $this->studiengang_model->result->retval;
            }
            else
            {
                //TODO could not load data
            }
            $this->load->view('studiengaenge', $this->_data);
        }
        else
        {
            //TODO studiensemester not found
        }
    }
}
