<?php

class Person extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('person_model', 'PersonModel');
        $this->load->model('kontakt_model', 'KontaktModel');
        $this->load->model('nation_model');
        $this->load->model('bundesland_model');
        $this->load->helper("form");
        $this->load->library("form_validation");
    }

    public function index() {
        $this->_data['title'] = 'Personendaten';
        
        if(!is_null($this->session->person_id))
        {
            //load person data
            if($this->PersonModel->getPersonen(array("person_id"=>$this->session->person_id)))
            {
                if(($this->PersonModel->result->error == 0) && (count($this->PersonModel->result->retval) == 1))
                {
                    $this->_data["person"] = $this->PersonModel->result->retval[0];
                    var_dump($this->_data["person"]);
                }
                
            }
            
            //load kontakt data
            if($this->KontaktModel->getKontakt($this->session->person_id))
            {
                if(($this->KontaktModel->result->error == 0))
                {   
                    foreach($this->KontaktModel->result->retval as $value)
                    {
                        $this->_data["kontakt"][$value->kontakttyp] = $value;
                    }
                }
                
            }
            
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
            
            //form validation rules
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
            $this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
//            $this->form_validation->set_rules("geb_datum", "Geburtsdatum", "required");
            $this->form_validation->set_rules("email", "E-Mail", "required|valid_email");

            if ($this->form_validation->run() == FALSE)
            {
                    $this->load->view('person', $this->_data);
            }
            else
            {
                $post = $this->input->post();
                var_dump($post);
                $this->_savePerson($this->_data["person"]);
                
                //TODO save Adresse
                $adresse = new stdClass();
                $adresse->person_id = $this->_data["person"]->person_id;
                $adresse->strasse = $post["strasse"];
                $adresse->plz = $post["plz"];
                
                //TODO save new contact
                if(($post["telefon"] != "") && !(isset($this->_data["kontakt"]["telefon"])))
                {
                    $kontakt = new stdClass();
                    $kontakt->person_id = $this->_data["person"]->person_id;
                    $kontakt->kontakttyp = "telefon";
                    $kontakt->kontakt = $post["telefon"];
                    $this->_saveKontakt($kontakt);
                }
                
                if(($post["fax"] != "") && !(isset($this->_data["kontakt"]["fax"])))
                {
                    $kontakt = new stdClass();
                    $kontakt->person_id = $this->_data["person"]->person_id;
                    $kontakt->kontakttyp = "fax";
                    $kontakt->kontakt = $post["fax"];
                    $this->_saveKontakt($kontakt);
                }
                
                foreach($this->_data["kontakt"] as $key=>$kontakt)
                {
                    $kontakt->kontakt = $post[$key];
                    $this->_saveKontakt($kontakt);
                }
                $this->load->view('person', $this->_data);
            }
        }
        else
        {
            redirect("/Login");
        } 
    }
    
    private function _savePerson($person)
    {
        $post = $this->input->post();

        $person->anrede = $post["anrede"];
        $person->gebdatum = $post["gebdatum"];
        $person->gebort = $post["geburtsort"];
        $person->geburtsnation = $post["nation"];
        $person->geschlecht = $post["geschlecht"];
        $person->staatsbuergerschaft = $post["staatsbuergerschaft"];
        $person->svnr = $post["svnr"];
        $person->titelpre = $post["titelpre"];

        if($this->PersonModel->savePerson($person))
        {
            if($this->PersonModel->result->error == 0)
            {
                //TODO Daten erfolgreich gespeichert
            }
            else
            {
                //TODO Daten konnten nicht gespeichert werden
            }
        }
    }
    
    private function _saveKontakt($kontakt)
    {
        if($this->KontaktModel->saveKontakt($kontakt))
        {
            if($this->KontaktModel->result->error == 0)
            {
                //TODO Daten erfolgreich gespeichert
            }
            else
            {
                //TODO Daten konnten nicht gespeichert werden
            }
        }
    }

}
