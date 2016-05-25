<?php

class Summary extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('summary', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('person_model', "PersonModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('nation_model', "NationModel");
        $this->load->model('adresse_model', "AdresseModel");
        $this->load->model('bundesland_model', "BundeslandModel");
        $this->load->model('kontakt_model', "KontaktModel");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        //load nationen
        $this->_loadNationen();
        
        //load bundeslaender
        $this->_loadBuendeslaender();
        
        //load person
        $this->_loadPerson();
        
        //load adresse
        $this->_loadAdresse();
        
        //load kontakt
        $this->_loadKontakt();
        
        //load prestudent
        $this->_loadPrestudent();
        
        //load studiengang
        foreach($this->_data["prestudent"] as $prestudent)
        {
            if($prestudent->studiengang_kz == $this->session->userdata()["studiengang_kz"])
            {
                $this->_loadStudiengang($prestudent->studiengang_kz);
            }
        }
        
        $this->load->view('summary', $this->_data);
    }
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["prestudent"]->studiengang_kz;
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
    
    private function _loadPerson()
    {
        if($this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"])))
        {
            if(($this->PersonModel->result->error == 0) && (count($this->PersonModel->result->retval) == 1))
            {
                $person = $this->PersonModel->result->retval[0];
                foreach($this->_data["nationen"] as $nation)
                {
                    if($nation->nation_code == $person->staatsbuergerschaft)
                    {
                        $person->staatsbuergerschaft = $nation->kurztext;
                    }
                    
                    if($nation->nation_code == $person->geburtsnation)
                    {
                        $person->geburtsnation = $nation->kurztext;
                    }
                }
                
                foreach($this->_data["bundeslaender"] as $bundesland)
                {
                    if($bundesland->bundesland_code == $person->bundesland_code)
                    {
                        $person->bundesland_bezeichnung = $bundesland->bezeichnung;
                    }
                   
                }
                
                $time = strtotime($person->gebdatum);
                $person->gebdatum = date('d.m.Y',$time);
                
                $this->_data["person"] = $person;
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
    
    private function _loadNationen()
    {
        if($this->NationModel->getNationen())
        {
            if($this->NationModel->result->error == 0)
            {
                $this->_data["nationen"] = $this->NationModel->result->retval;        
            }
        }
    }
    
    private function _loadBuendeslaender()
    {
        if($this->BundeslandModel->getBundeslaender())
        {
            if($this->BundeslandModel->result->error == 0)
            {
                $this->_data["bundeslaender"] = $this->BundeslandModel->result->retval;        
            }
        }
    }
    
    private function _loadAdresse()
    {
        if($this->AdresseModel->getAdresse($this->session->userdata()["person_id"]))
        {
            if($this->AdresseModel->result->error == 0)
            {
                foreach($this->AdresseModel->result->retval as $adresse)
                {
                    if($adresse->heimatadresse = "t")
                    {
                        $this->_data["adresse"] = $adresse;
                    }
                }
            }
            
            if(!isset($this->_data["adresse"]))
            {
                $this->_data["adresse"] = new stdClass();
            }
        }
    }
    
    private function _loadKontakt()
    {
        if($this->KontaktModel->getKontakt($this->session->userdata()["person_id"]))
        {
            if($this->KontaktModel->result->error == 0)
            {
                foreach($this->KontaktModel->result->retval as $kontakt)
                {
                    $this->_data["kontakt"][$kontakt->kontakttyp] = $kontakt;  
                }  
            }
        }
    }
}
