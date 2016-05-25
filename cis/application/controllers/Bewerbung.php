<?php

class Bewerbung extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('person_model', 'PersonModel');
        $this->load->model('kontakt_model', 'KontaktModel');
        $this->load->model('nation_model');
        $this->load->model('bundesland_model');
        $this->load->model('adresse_model', "AdresseModel");
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        $this->load->model('studiensemester_model', "StudiensemesterModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->helper("form");
        $this->load->library("form_validation");
    }

    public function index() 
    {    
        $this->checkLogin();
        
        if(!isset($this->session->userdata()["studiengang_kz"]))
        {
            redirect("/Studiengaenge");
        }
        
        $this->_data['title'] = 'Personendaten';
        
        //load person data
        $this->_loadPerson();

        //load kontakt data
        $this->_loadKontakt();
        
        //load preinteressent data
        $this->_loadPrestudent();
        
        //load studiengang
        $this->_loadStudiengang();

        //load adress data
        $this->_loadAdresse();

        //load nationen
        $this->_loadNationen();

        //load bundeslaender
        $this->_loadBundeslaender();

        //form validation rules
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
        $this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
//            $this->form_validation->set_rules("geb_datum", "Geburtsdatum", "required");
        $this->form_validation->set_rules("email", "E-Mail", "required|valid_email");

        if ($this->form_validation->run() == FALSE)
        {
                $this->load->view('bewerbung', $this->_data);
        }
        else
        {
            $post = $this->input->post();
            $this->_savePerson($this->_data["person"]);

            //TODO save Adresse
            $adresse = new stdClass();
            if(isset($this->_data["adresse"]))
            {
                $adresse = $this->_data["adresse"];
            }
            else
            {
                $adresse->heimatadresse = true;
            }
            $adresse->person_id = $this->_data["person"]->person_id;
            $adresse->strasse = $post["strasse"];
            $adresse->plz = $post["plz"];
            $adresse->ort = $post["ort"];

            $this->_saveAdresse($adresse);

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
            $this->load->view('bewerbung', $this->_data);
        }
    }
    
    public function studiengang($studiengang_kz, $studienplan_id)
    {        
        $this->checkLogin();
        
        $this->session->set_userdata("studiengang_kz", $studiengang_kz);
        
        //load person data
        $this->_loadPerson();

        //load preinteressent data
        $this->_loadPrestudent();
        
        //load Studienplan
        $this->_loadStudienplan($studienplan_id);
        
        if((!isset($this->_data["prestudent"]) ||(count($this->_data["prestudent"]) == 0))&& ($this->PrestudentModel->result->error == 0))
        {
            $prestudent = $this->_savePrestudent($studiengang_kz);
            var_dump($prestudent);
            $this->_loadPrestudent();
            $this->_savePrestudentStatus($prestudent);
        }
        else
        {
            foreach($this->_data['prestudent'] as $prestudent)
            {
                $this->_loadPrestudentStatus($prestudent->prestudent_id, $studiengang_kz);
                /*
                if(!isset($this->_data["preinteressentStudiengang"]))
                {
                    $this->_savePreinteressentStudiengang($preinteressent, $studiengang_kz);
                }*/
            }
        }

        //load kontakt data
        $this->_loadKontakt();
        
        //load nationen
        $this->_loadNationen();

        //load bundeslaender
        $this->_loadBundeslaender();
        
        //load studiengang
        $this->_loadStudiengang($studiengang_kz);
        
        $this->load->view('bewerbung', $this->_data);
    }
    
    private function _loadPerson()
    {
        if($this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"])))
        {
            if(($this->PersonModel->result->error == 0) && (count($this->PersonModel->result->retval) == 1))
            {
                $this->_data["person"] = $this->PersonModel->result->retval[0];
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
    
    private function _loadPrestudentStatus($prestudent_id, $studiengang_kz)
    {
        if($this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiengang_kz"=>$studiengang_kz)))
        {
            if(($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
            {
                foreach($this->_data["prestudent"] as $key=>$value)
                {
                    if($value->prestudent_id == $prestudent_id)
                    {
                        $this->_data["prestudent"][$key]->prestudentstatus = $this->PrestudentStatusModel->result->retval[0];
                    }
                }
                $this->_data["prestudent"]["prestudentstatus"] = $this->PrestudentStatusModel->result->retval;
            }
        }
    }

    private function _loadKontakt()
    {
        if($this->KontaktModel->getKontakt($this->session->userdata()["person_id"]))
        {
            if(($this->KontaktModel->result->error == 0))
            {   
                foreach($this->KontaktModel->result->retval as $value)
                {
                    $this->_data["kontakt"][$value->kontakttyp] = $value;
                }
            }
        }
    }
    
    private function _loadAdresse()
    {
        if($this->AdresseModel->getAdresse($this->session->userdata()["person_id"]))
        {
            if(($this->AdresseModel->result->error == 0))
            {   
                foreach($this->AdresseModel->result->retval as $adresse)
                {
                    if($adresse->heimatadresse == "t")
                    {
                        $this->_data["adresse"] = $adresse;
                    }
                }
            }
        }
    }
    
    private function _loadNationen()
    {
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
    }
    
    private function _loadBundeslaender()
    {
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
    }
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["prestudent"][0]->studiengang_kz;
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
    
    private function _loadStudienplan($studienplan_id)
    {
        if($this->StudienplanModel->getStudienplan($studienplan_id))
        {
            if(($this->StudienplanModel->result->error == 0) && (count($this->StudienplanModel->result->retval) == 1))
            {
                $this->_data["studienplan"] = $this->StudienplanModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
            }
        }
    }

    private function _savePerson($person)
    {
        $post = $this->input->post();

        $person->anrede = $post["anrede"];
        $person->bundesland_code = $post["bundesland"];
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
    
    private function _savePrestudent($studiengang_kz)
    {
        $prestudent = new stdClass();
        $prestudent->person_id = $this->session->userdata()["person_id"];
        $prestudent->studiengang_kz = $studiengang_kz;
        //TODO welches Studiensemester soll gewählt werden
        $prestudent->aufmerksamdurch_kurzbz = 'k.A.';
        var_dump($prestudent);
        var_dump($this->PrestudentModel->savePrestudent($prestudent));
        if($this->PrestudentModel->savePrestudent($prestudent))
        {
            var_dump($this->PrestudentModel->result);
            if($this->PrestudentModel->result->error == 0)
            {
                //TODO Daten erfolgreich gespeichert
                $prestudent->prestudent_id = $this->PrestudentModel->result->retval;
                return $prestudent;
            }
            else
            {
                //TODO Daten konnten nicht gespeichert werden
            }
        }
    }
    
    private function _savePrestudentStatus($prestudent)
    {
        $prestudentStatus = new stdClass();
        $prestudentStatus->new = true;
        $prestudentStatus->prestudent_id = $prestudent->prestudent_id;
        $prestudentStatus->status_kurzbz = "Interessent";
        
        $this->StudiensemesterModel->getNextStudiensemester("WS");
        
        if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
        {
            $studiensemester_kurzbz = $this->StudiensemesterModel->result->retval[0]->studiensemester_kurzbz;
            $prestudentStatus->studiensemester_kurzbz = $studiensemester_kurzbz;
            //nicht notwendig da defaultwert 1
//            $prestudentStatus->ausbildungssemester = "1";
            $prestudentStatus->orgform_kurzbz = $this->_data["studienplan"]->orgform_kurzbz;
            $prestudentStatus->studienplan_id = $this->_data["studienplan"]->studienplan_id;
            $prestudentStatus->datum = date("Y-m-d");

            if($this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus))
            {
                var_dump($this->PrestudentStatusModel->result);
                if($this->PrestudentStatusModel->result->error == 0)
                {
                    //TODO Daten erfolgreich gespeichert
                    foreach($this->_data["prestudent"] as $key=>$value)
                    {
                        if($value->prestudent_id == $prestudent->prestudent_id)
                        {
                            $this->_data["prestudent"][$key]->prestudentstatus = $prestudentStatus;
                        }
                    }
                }
                else
                {
                    //TODO Daten konnten nicht gespeichert werden
                }
            }
        }
        else
        {
            //TODO studiensemester not found
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
    
    private function _saveAdresse($adresse)
    {
        if($this->AdresseModel->saveAdresse($adresse))
        {
            if($this->AdresseModel->result->error == 0)
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
