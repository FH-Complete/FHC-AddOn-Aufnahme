<?php

class Send extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('send', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        
        $this->load->helper("form");
        $this->load->library("form_validation");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        if(isset($this->input->get()["studiengang_kz"]))
        {           
            //load studiengang
            $this->_loadStudiengang($this->input->get()["studiengang_kz"]);

            //load preinteressent data
            $this->_loadPrestudent();

            //load prestudent data for correct studiengang
            foreach($this->_data["prestudent"] as $prestudent)
            {
                //load studiengaenge der prestudenten
                if($prestudent->studiengang_kz == $this->session->userdata()["studiengang_kz"])
                {
                    $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                    $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);         
                    $this->_data["studiengang"]->studienplan = $studienplan;
                } 
            }
            
            $this->load->view('send', $this->_data);
        }
        else
        {
            //TODO error studiengang_kz fehlt
            echo "studiengang_kz fehlt";
        }
    }
    
    public function send($studiengang_kz, $studienplan_id)
    {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        //load studiengang
        $this->_loadStudiengang($studiengang_kz);
        
        $this->_loadPrestudent();
        
        //load prestudent data for correct studiengang
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            if($prestudent->studiengang_kz == $this->session->userdata()["studiengang_kz"])
            {
                $prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                $studienplan = $this->_loadStudienplan($prestudentStatus->studienplan_id);         
                $this->_data["studiengang"]->studienplan = $studienplan;
                
                //TODO check if status exists
                if(is_null($prestudentStatus->bestaetigtam))
                {
                    $prestudentStatus->bestaetigtam=date('Y-m-d H:i:s');
                    $this->_savePrestudentStatus($prestudentStatus);
                    
                    //TODO send mails
                }
                else
                {
                    //TODO bewerbung bereits abgeschickt;
                }

                $this->load->view('send', $this->_data);
            } 
        }
    }
    
    private function _loadStudiengang($studiengang_kz)
    {
        if($this->StudiengangModel->getStudiengang($studiengang_kz))
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
    
    private function _loadStudienplan($studienplan_id)
    {
        if($this->StudienplanModel->getStudienplan($studienplan_id))
        {
            if(($this->StudienplanModel->result->error == 0) && (count($this->StudienplanModel->result->retval) == 1))
            {
                return $this->StudienplanModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
            }
        }
    }
    
    private function _loadPrestudent()
    {
        if($this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"])))
        {
            if($this->PrestudentModel->result->error == 0)
            {
                $this->_data["prestudent"] = $this->PrestudentModel->result->retval;        
            }
        }
    }
    
    private function _loadPrestudentStatus($prestudent_id)
    {
        if($this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent")))
        {
            if(($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
            {
                return $this->PrestudentStatusModel->result->retval[0];
            }
        }
    }
    
    private function _savePrestudentStatus($prestudentStatus)
    {
        if($this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus))
        {
            if($this->PrestudentStatusModel->result->error == 0)
            {
                //TODO datan erfolgreich gespeichert
            }
            else
            {
                //TODO Daten konnten nicht gespeichert werden
            }
        }
    }
}
