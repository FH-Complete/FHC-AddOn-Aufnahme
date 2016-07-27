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
        $this->load->model('dms_model', "DmsModel");
        $this->load->model('akte_model', "AkteModel");
	$this->load->model('gemeinde_model', "GemeindeModel");
	$this->load->model('Bewerbungstermine_model', 'BewerbungstermineModel');
        $this->load->helper("form");
        $this->load->library("form_validation");
	$this->_data["sprache"] = $this->get_language();
	$this->_loadLanguage($this->_data["sprache"]);
    }

    public function index() 
    {   
        $this->checkLogin();
        
        $this->_data['title'] = 'Personendaten';
        
        //$this->StudiensemesterModel->getNextStudiensemester("WS");
        $this->session->set_userdata("studiensemester_kurzbz", $this->_getNextStudiensemester("WS"));
        
        //load person data
        $this->_data["person"] = $this->_loadPerson();

        //load kontakt data
        $this->_loadKontakt();
        
        //load preinteressent data
        $this->_data["prestudent"] = $this->_loadPrestudent();
        
        $this->_data["studiengaenge"] = array();
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
	    
            $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
	    
	    if(!empty($prestudent->prestudentStatus))
	    {
		$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
		$studiengang->studienplan = $studienplan;
		array_push($this->_data["studiengaenge"], $studiengang);
	    }
        }
        
        if(count($this->_data["studiengaenge"]) == 0)
        {
            redirect("/Studiengaenge");
        }
        
        //load adress data
        $this->_loadAdresse();

        //load nationen
        $this->_loadNationen();

        //load bundeslaender
        $this->_loadBundeslaender();
	
	//load gemeinden
	$this->_getGemeinde();
	
	$this->_data["plz"] = array();
	$this->_data["zustell_plz"] = array();
	foreach($this->_data["gemeinden"] as $gemeinde)
	{
	    $this->_data["plz"][$gemeinde->gemeinde_id] = $gemeinde->plz." ".$gemeinde->name.", ".$gemeinde->ortschaftsname;
	    if(isset($this->_data["gemeinde"]) && $this->_data["plz"][$gemeinde->gemeinde_id] === $this->_data["gemeinde"])
	    {
		$this->_data["gemeinde_id"] = $gemeinde->gemeinde_id;
	    }
	    
	    $this->_data["zustell_plz"][$gemeinde->gemeinde_id] = $gemeinde->plz." ".$gemeinde->name.", ".$gemeinde->ortschaftsname;
	    if(isset($this->_data["zustell_gemeinde"]) && $this->_data["zustell_plz"][$gemeinde->gemeinde_id] === $this->_data["zustell_gemeinde"])
	    {
		$this->_data["zustell_gemeinde_id"] = $gemeinde->gemeinde_id;
	    }
	}
        
        //load dokumente
        $this->_loadDokumente($this->session->userdata()["person_id"]);
	
	foreach($this->_data["dokumente"] as $akte)
	{
	    if($akte->dms_id != null)
	    {
		$dms = $this->_loadDms($akte->dms_id);
		$akte->dokument = $dms;
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
//	    $this->_checkRequiredFields();
	    $this->load->view('bewerbung', $this->_data);
        }
        else
        {
            $post = $this->input->post();
            $files = $_FILES;
            
            if(count($files) > 0)
            {
                foreach($files as $key=>$file)
                {
                    if(is_uploaded_file($file["tmp_name"]))
                    {
                        $obj = new stdClass();
			$akte = new stdClass();
			
                        $obj->version = 0;
                        $obj->mimetype = $file["type"];
                        $obj->name = $file["name"];
                        $obj->oe_kurzbz = null;

                        switch($key)
                        {
                            case "reisepass":
                                $obj->dokument_kurzbz = "pass";
                                break;                        
                            case "lebenslauf":
                                $obj->dokument_kurzbz = "Lebenslf";
                                break;
                            default:
                                $obj->dokument_kurzbz = "Sonst";
                                break;
                        }
			
			foreach($this->_data["dokumente"] as $akte_temp)
			{
			    if(($akte_temp->dokument_kurzbz == $obj->dokument_kurzbz) && ($obj->dokument_kurzbz != "Sonst"))
			    {
				$dms = $this->_loadDms($akte_temp->dms_id);
				$obj->version = $dms->version+1;
				
				if($akte_temp->dms_id != null)
				{
				    $dms = $this->_loadDms($akte_temp->dms_id);
				    $obj->version = $dms->version+1;
				}
				else
				{
				    $akte = $akte_temp;
				    $akte->updateamum = date("Y-m-d H:i:s");
				    $akte->updatevon = "online";
				}
			    }
			}

                        $obj->kategorie_kurzbz = "Akte";

                        $type = pathinfo($file["name"], PATHINFO_EXTENSION);
                        $data = file_get_contents($file["tmp_name"]);
                        $obj->file_content = 'data:image/' . $type . ';base64,' . base64_encode($data);
			
			$this->_saveDms($obj);

                        if($this->DmsModel->result->error == 0)
                        {
                            $akte->dms_id = $this->DmsModel->result->retval->dms_id;
                            $akte->person_id = $this->_data["person"]->person_id;
                            $akte->mimetype = $file["type"];

                            $akte->bezeichnung = mb_substr($obj->name, 0, 32);
                            $akte->dokument_kurzbz = $obj->dokument_kurzbz;
                            $akte->titel = $key;
                            $akte->insertvon = 'online';
			    $akte->nachgereicht = 'f';
			    
			    unset($akte->uid);
			    unset($akte->inhalt_vorhanden);
			    
			    $this->_saveAkte($akte);
                        }
			else
			{
			    //TODO handle error
			    var_dump($this->DmsModel->result);
			}

                        if(unlink($file["tmp_name"]))
                        {
                            //removing tmp file successful
                        }
                    }
		    else
		    {
			if(isset($post["reisepass_nachgereicht"]))
			{
			    $akte = new stdClass();
			    $akte->person_id = $this->_data["person"]->person_id;

			    $akte->bezeichnung = $file["name"];
			    $akte->dokument_kurzbz = "pass";
			    $akte->insertvon = 'online';
			    $akte->nachgereicht = true;

			    $this->_saveAkte($akte);
			}
			
			if(isset($post["lebenslauf_nachgereicht"]))
			{
			    $akte = new stdClass();
			    $akte->person_id = $this->_data["person"]->person_id;

			    $akte->bezeichnung = $file["name"];
			    $akte->dokument_kurzbz = "Lebenslf";
			    $akte->insertvon = 'online';
			    $akte->nachgereicht = true;

			    $this->_saveAkte($akte);
			}
		    }
		    
		    //load dokumente
		    $this->_loadDokumente($this->session->userdata()["person_id"]);

		    foreach($this->_data["dokumente"] as $akte)
		    {
			if($akte->dms_id != null)
			{
			    $dms = $this->_loadDms($akte->dms_id);
			    $akte->dokument = $dms;
			}
		    }
                }
            }

	    $person = $this->_data["person"];
	    $person->anrede = $post["anrede"];
	    //$person->bundesland_code = $post["bundesland"];
	    if(isset($post["geb_datum"]))
	    {
		$person->gebdatum = date('Y-m-d', strtotime($post["geb_datum"]));
	    }
	    $person->gebort = $post["geburtsort"];
	    $person->geburtsnation = $post["nation"];
	    $person->geschlecht = isset($post["geschlecht"]) ? $post["geschlecht"] : "u";
	    $person->staatsbuergerschaft = $post["staatsbuergerschaft"];
	    $person->svnr = $post["svnr"];
	    $person->titelpre = $post["titelpre"];
	    $person->titelpost = $post["titelpost"];
	    
	    $this->_savePerson($person);
	    
	    $adresse = new stdClass();
	    $zustell_adresse = new stdClass();
	    if($post["adresse_nation"] === "A")
	    {
		if(isset($this->_data["adresse"]))
		{
		    $adresse = $this->_data["adresse"];
		}
		else
		{
		    $adresse->person_id = $this->_data["person"]->person_id;
		}
		
		$adresse->strasse = $post["strasse"];
		$adresse->nation = $post["adresse_nation"];
		
		foreach($this->_data["gemeinden"] as $gemeinde)
		{
		    if($gemeinde->gemeinde_id === $post["plzOrt"])
		    {
			$adresse->plz = $gemeinde->plz;
			$adresse->ort = $gemeinde->ortschaftsname;
			$adresse->gemeinde = $gemeinde->name;
		    }
		}

		foreach($this->_data["bundesland"] as $bundesland)
		{
		    if($bundesland->bundesland_code === $gemeinde->bulacode)
		    {
			$person->bundesland_code = $bundesland->bundesland_code;
		    }
		}
		
		$this->_saveAdresse($adresse);
	    }
	    
	    if($post["zustelladresse_nation"] === "A")
	    {
		if(isset($this->_data["zustell_adresse"]))
		{
		    $zustell_adresse = $this->_data["zustell_adresse"];
		}
		else
		{
		    $zustell_adresse->person_id = $this->_data["person"]->person_id;
		}
		
		$zustell_adresse->strasse = $post["zustell_strasse"];
		$zustell_adresse->nation = $post["zustelladresse_nation"];
		
		foreach($this->_data["gemeinden"] as $gemeinde)
		{
		    if($gemeinde->gemeinde_id === $post["zustell_plzOrt"])
		    {
			$zustell_adresse->plz = $gemeinde->plz;
			$zustell_adresse->ort = $gemeinde->ortschaftsname;
			$zustell_adresse->gemeinde = $gemeinde->name;
		    }
		}

		foreach($this->_data["bundesland"] as $bundesland)
		{
		    if($bundesland->bundesland_code === $gemeinde->bulacode)
		    {
			$person->bundesland_code = $bundesland->bundesland_code;
		    }
		}
		
		$this->_saveAdresse($adresse);
	    }

	    if(($post["strasse"] != "") && ($post["plz"] != "") && ($post["ort"] != ""))
	    {
		if(isset($this->_data["adresse"]))
		{
		    $adresse = $this->_data["adresse"];
		}
		else
		{
		    $adresse->heimatadresse = true;
		}
		
		if(($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != "")) || ($post["zustell_plzOrt"] != "")))
		{
		    $adresse->zustelladresse = "f";
		}
		else
		{
		    $adresse->zustelladresse = true;
		}
		
		$adresse->person_id = $this->_data["person"]->person_id;
		$adresse->strasse = $post["strasse"];
		
		if($post["adresse_nation"] !== "A")
		{
		    $adresse->plz = $post["plz"];
		    $adresse->ort = $post["ort"];
		    $adresse->nation = $post["adresse_nation"];
		}
		
		$this->_saveAdresse($adresse);
	    }
	    
	    if(($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != "")) || ($post["zustell_plzOrt"] != "")))
	    {
		if(isset($this->_data["zustell_adresse"]))
		{
		    $zustell_adresse = $this->_data["zustell_adresse"];
		}
		else
		{
		    $zustell_adresse->heimatadresse = "f";
		    $zustell_adresse->zustelladresse = "t";
		}
		$zustell_adresse->person_id = $this->_data["person"]->person_id;
		$zustell_adresse->strasse = $post["zustell_strasse"];
		
		if($post["zustelladresse_nation"] !== "A")
		{
		    $zustell_adresse->plz = $post["zustell_plz"];
		    $zustell_adresse->ort = $post["zustell_ort"];
		    $zustell_adresse->nation = $post["zustelladresse_nation"];
		}

		$this->_saveAdresse($zustell_adresse);
	    }

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
	    
	    $this->_loadPerson();
	    $this->_loadKontakt();
	    $this->_loadAdresse();
	    $this->_getGemeinde();
	    
	    $this->_data["plz"] = array();
	    $this->_data["zustell_plz"] = array();
	    foreach($this->_data["gemeinden"] as $gemeinde)
	    {
		$this->_data["plz"][$gemeinde->gemeinde_id] = $gemeinde->plz." ".$gemeinde->name.", ".$gemeinde->ortschaftsname;
		if(isset($this->_data["gemeinde"]) && $this->_data["plz"][$gemeinde->gemeinde_id] === $this->_data["gemeinde"])
		{
		    $this->_data["gemeinde_id"] = $gemeinde->gemeinde_id;
		}

		$this->_data["zustell_plz"][$gemeinde->gemeinde_id] = $gemeinde->plz." ".$gemeinde->name.", ".$gemeinde->ortschaftsname;
		if(isset($this->_data["zustell_gemeinde"]) && $this->_data["zustell_plz"][$gemeinde->gemeinde_id] === $this->_data["zustell_gemeinde"])
		{
		    $this->_data["zustell_gemeinde_id"] = $gemeinde->gemeinde_id;
		}
	    }
	    
//	    $this->_checkRequiredFields();
            $this->load->view('bewerbung', $this->_data);
        }
    }
    
    public function studiengang($studiengang_kz, $studienplan_id)
    {        
        $this->checkLogin();
        
        $this->session->set_userdata("studiengang_kz", $studiengang_kz);
        
        //$this->StudiensemesterModel->getNextStudiensemester("WS");
        $this->session->set_userdata("studiensemester_kurzbz", $this->_getNextStudiensemester("WS"));
        
        //load person data
        $this->_data["person"] = $this->_loadPerson();

        //load preinteressent data
        $this->_data["prestudent"] = $this->_loadPrestudent();
        
        //load Studienplan
        $this->_data["studienplan"] = $this->_loadStudienplan($studienplan_id); 
	
	$fristen = $this->_getBewerbungstermine($studiengang_kz, $this->session->userdata()["studiensemester_kurzbz"]);
	
	$bewerbungMoeglich = false;
	if(!empty($fristen))
	{
	    foreach($fristen as $frist)
	    {
		if((date("Y-m-d", strtotime($frist->beginn)) < date("Y-m-d")) && (date("Y-m-d", strtotime($frist->ende)) > date("Y-m-d")))
		{
		    $bewerbungMoeglich = true;
		}
	    }
	}
	
	if($bewerbungMoeglich)
	{
	    $exists = false;
	    $prestudentStatus = null;
	    foreach($this->_data["prestudent"] as $prestudent)
	    {
		$prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
		if(($prestudent->studiengang_kz == $studiengang_kz) && (!is_null($prestudentStatus)) && ($prestudentStatus->studienplan_id == $studienplan_id))
		{
		    $exists = true;
		}
		$prestudentStatus = null;
	    }

	    if((!$exists) && ($this->PrestudentModel->result->error == 0))
	    {
		$prestudent = $this->_savePrestudent($studiengang_kz);
		$this->_data["prestudent"] = $this->_loadPrestudent();
		$this->_savePrestudentStatus($prestudent);
	    }
	    else
	    {
		//TODO handle error
		if($this->PrestudentModel->result->error != 0)
		{
		    var_dump($this->PrestudentModel->result);
		}
	    }
	}
	else
	{
	    redirect("/Studiengaenge");
	}
	
	//load adress data
        $this->_loadAdresse();

        //load kontakt data
        $this->_loadKontakt();
        
        //load nationen
        $this->_loadNationen();

        //load bundeslaender
        $this->_loadBundeslaender();
	
	//load gemeinden
	$this->_data["gemeinden"] = $this->_loadGemeinde();
	if(isset($this->_data["adresse"]))
	{
	    $this->_data["gemeinde"] = $this->_data["adresse"]->plz." ".$this->_data["adresse"]->gemeinde.", ".$this->_data["adresse"]->ort;
	}
	
	$this->_data["plz"] = array();
	foreach($this->_data["gemeinden"] as $gemeinde)
	{
	    $this->_data["plz"][$gemeinde->gemeinde_id] = $gemeinde->plz." ".$gemeinde->name.", ".$gemeinde->ortschaftsname;
	    if(isset($this->_data["gemeinde"]) && $this->_data["plz"][$gemeinde->gemeinde_id] === $this->_data["gemeinde"])
	    {
		$this->_data["gemeinde_id"] = $gemeinde->gemeinde_id;
	    }
	}
        
        //load studiengang
        $this->_data["studiengang"] = $this->_loadStudiengang($studiengang_kz);

         $this->_data["studiengaenge"] = array();
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
            $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
            $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
            $studiengang->studienplan = $studienplan;
            array_push($this->_data["studiengaenge"], $studiengang);
        }
        
        $this->load->view('bewerbung', $this->_data);
    }
    
    private function _loadPerson()
    {
	$this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"]));
        if($this->PersonModel->isResultValid() === true)
        {
            if(count($this->PersonModel->result->retval) == 1)
            {
                return $this->PersonModel->result->retval[0];
            }
	    else
	    {
		return $this->PersonModel->result->retval;
	    }
        }
	else
	{
	    $this->_setError(true, $this->PersonModel->getErrorMessage());
	}
    }
    
    private function _loadPrestudent()
    {
	$this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"]));
        if($this->PrestudentModel->isResultValid() === true)
        {
	    return $this->PrestudentModel->result->retval;        
        }
	else
	{
	    $this->_setError(true, $this->PrestudentModel->getErrorMessage());
	}
    }
    
    private function _loadPrestudentStatus($prestudent_id)
    {
	$this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
        if($this->PrestudentStatusModel->isResultValid() === true)
        {
            if(($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
            {
                return $this->PrestudentStatusModel->result->retval[0];
            }
	    else
	    {
		return $this->PrestudentStatusModel->result->retval;
	    }
        }
	else
	{
	    $this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
	}
    }

    private function _loadKontakt()
    {
	$this->KontaktModel->getKontakt($this->session->userdata()["person_id"]);
        if($this->KontaktModel->isResultValid() === true)
        {
	    foreach($this->KontaktModel->result->retval as $value)
	    {
		$this->_data["kontakt"][$value->kontakttyp] = $value;
	    }
        }
	else
	{
	    $this->_setError(true, $this->KontaktModel->getErrorMessage());
	}
    }
    
    private function _loadAdresse()
    {
	$this->AdresseModel->getAdresse($this->session->userdata()["person_id"]);
        if($this->AdresseModel->isResultValid() === true)
        {
	    foreach($this->AdresseModel->result->retval as $adresse)
	    {
		if($adresse->heimatadresse == "t")
		{
		    $this->_data["adresse"] = $adresse;
		}
		else if(($adresse->heimatadresse == "f") && ($adresse->zustelladresse == "t"))
		{
		    $this->_data["zustell_adresse"] = $adresse;
		}
	    }
        }
	else
	{
	    $this->_setError(true, $this->AdresseModel->getErrorMessage());
	}
    }
    
    private function _loadNationen()
    {
	$this->nation_model->getNationen();
        if($this->nation_model->isResultValid() === true)
        {
	    foreach($this->nation_model->result->retval as $n)
	    {
		$this->_data["nationen"][$n->nation_code] = $n->kurztext;
	    }
        }
	else
	{
	    $this->_setError(true, $this->nation_model->getErrorMessage());
	}
    }
    
    private function _loadBundeslaender()
    {
	$this->bundesland_model->getBundeslaender();
        if($this->bundesland_model->isResultValid() === true)
        {
	    $this->_data["bundesland"] = $this->bundesland_model->result->retval;
	    foreach($this->bundesland_model->result->retval as $b)
	    {
		$this->_data["bundeslaender"][$b->bundesland_code] = $b->bezeichnung;
	    }
        }
	else
	{
	    $this->_setError(true, $this->bundesland_model->getErrorMessage());
	}
    }
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["prestudent"][0]->studiengang_kz;
        }
	
	$this->StudiengangModel->getStudiengang($stgkz);
        if($this->StudiengangModel->isResultValid() === true)
        {
            if(count($this->StudiengangModel->result->retval) == 1)
            {
                return $this->StudiengangModel->result->retval[0];
            }
            else
            {
                return $this->StudiengangModel->result->retval;
            }
        }
	else
	{
	    $this->_setError(true, $this->StudiengangModel->getErrorMessage());
	}
    }
    
    private function _loadStudienplan($studienplan_id)
    {
	$this->StudienplanModel->getStudienplan($studienplan_id);
        if($this->StudienplanModel->isResultValid() === true)
        {
            if(count($this->StudienplanModel->result->retval) == 1)
            {
                return $this->StudienplanModel->result->retval[0];
            }
            else
            {
               return $this->StudienplanModel->result->retval;
            }
        }
	else
	{
	    $this->_setError(true, $this->StudienplanModel->getErrorMessage());
	}
    }

    private function _savePerson($person)
    {
	$this->PersonModel->savePerson($person);
        if($this->StudienplanModel->isResultValid() === true)
        {
	    //TODO Daten erfolgreich gespeichert
        }
	else
	{
	    $this->_setError(true, $this->PersonModel->getErrorMessage());
	}
    }
    
    private function _savePrestudent($studiengang_kz)
    {
        $prestudent = new stdClass();
        $prestudent->person_id = $this->session->userdata()["person_id"];
        $prestudent->studiengang_kz = $studiengang_kz;
        //TODO welches Studiensemester soll gewählt werden
        $prestudent->aufmerksamdurch_kurzbz = 'k.A.';
	$this->PrestudentModel->savePrestudent($prestudent);
        if($this->PrestudentModel->isResultValid() === true)
        {
	    //TODO Daten erfolgreich gespeichert
	    $prestudent->prestudent_id = $this->PrestudentModel->result->retval;
	    return $prestudent;
        }
	else
	{
	    $this->_setError(true, $this->PrestudentModel->getErrorMessage());
	}
    }
    
    private function _savePrestudentStatus($prestudent)
    {
        $prestudentStatus = new stdClass();
        $prestudentStatus->new = true;
        $prestudentStatus->prestudent_id = $prestudent->prestudent_id;
        $prestudentStatus->status_kurzbz = "Interessent";
	$prestudentStatus->rt_stufe = 1;
        
        if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
        {
            $prestudentStatus->studiensemester_kurzbz = $this->session->userdata()["studiensemester_kurzbz"];
            //nicht notwendig da defaultwert 1
//            $prestudentStatus->ausbildungssemester = "1";
            $prestudentStatus->orgform_kurzbz = $this->_data["studienplan"]->orgform_kurzbz;
            $prestudentStatus->studienplan_id = $this->_data["studienplan"]->studienplan_id;
            $prestudentStatus->datum = date("Y-m-d");

	    $this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
            if($this->PrestudentStatusModel->isResultValid() === true)
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
		$this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
	    }
        }
        else
        {
            //TODO studiensemester not found
	    $this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
        }
    }
    
    private function _saveKontakt($kontakt)
    {
	$this->KontaktModel->saveKontakt($kontakt);
        if($this->KontaktModel->isResultValid() === true)
        {
	    //TODO Daten erfolgreich gespeichert
        }
	else
	{
	    $this->_setError(true, $this->KontaktModel->getErrorMessage());
	}
    }
    
    private function _saveAdresse($adresse)
    {
	$this->AdresseModel->saveAdresse($adresse);
	if($this->AdresseModel->isResultValid() === true)
	{
	    //TODO Daten erfolgreich gespeichert
	}
	else
	{
	    $this->_setError(true, $this->AdresseModel->getErrorMessage());
	}
    }
    
    private function _loadDokumente($person_id, $dokumenttyp_kurzbz=null)
    {
        $this->_data["dokumente"] = array();
        $this->AkteModel->getAkten($person_id, $dokumenttyp_kurzbz);
        
        if($this->AkteModel->isResultValid() === true)
        {
            foreach($this->AkteModel->result->retval as $akte)
            {
                $this->_data["dokumente"][$akte->dokument_kurzbz] = $akte;
            }
        }
	else
	{
	    $this->_setError(true, $this->AkteModel->getErrorMessage());
	}
    }
    
    private function _loadDms($dms_id)
    {
        $this->DmsModel->loadDms($dms_id);
        if($this->DmsModel->isResultValid() === true)
        {
            if(count($this->DmsModel->result->retval) == 1)
	    {
		return $this->DmsModel->result->retval[0];
	    }
	    else
	    {
		$this->_setError(true, "Dokument konnte nicht gefunden werden.");
	    }
        }
	else
	{
	    $this->_setError(true, $this->DmsModel->getErrorMessage());
	}
    }
    
    private function _loadGemeinde()
    {
	$this->GemeindeModel->getGemeinde();
	if($this->GemeindeModel->isResultValid() === true)
	{
	    return $this->GemeindeModel->result->retval;
	}
	else
	{
	    $this->_setError(true, $this->GemeindeModel->getErrorMessage());
	}
    }
    
    private function _getNextStudiensemester($art)
    {
	$this->StudiensemesterModel->getNextStudiensemester($art);
	if($this->StudiensemesterModel->isResultValid() === true)
	{
	    return $this->StudiensemesterModel->result->retval[0]->studiensemester_kurzbz;
	}
	else
	{
	    $this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
	}
    }
    
    private function _saveDms($dms)
    {
	$this->DmsModel->saveDms($dms);
	if($this->DmsModel->isResultValid() === true)
	{
	    //TODO saved successfully
	}
	else
	{
	    $this->_setError(true, $this->DmsModel->getErrorMessage());
	}
    }
    
    private function _saveAkte($akte)
    {
	$this->AkteModel->saveAkte($akte);
	if($this->AkteModel->isResultValid() === true)
	{
	    //TODO saved successfully
	}
	else
	{
	    $this->_setError(true, $this->AkteModel->getErrorMessage());
	}
    }
    
    private function _getGemeinde()
    {
	if(!isset($this->_data["gemeinden"]))
	{
	    $this->_data["gemeinden"] = $this->_loadGemeinde();
	}
	if(isset($this->_data["adresse"]))
	{
	    $this->_data["gemeinde"] = $this->_data["adresse"]->plz." ".$this->_data["adresse"]->gemeinde.", ".$this->_data["adresse"]->ort;
	}
	
	if(isset($this->_data["zustell_adresse"]))
	{
	    $this->_data["zustell_gemeinde"] = $this->_data["zustell_adresse"]->plz." ".$this->_data["zustell_adresse"]->gemeinde.", ".$this->_data["zustell_adresse"]->ort;
	}
    }
    
    private function _getBewerbungstermine($studiengang_kz, $studiensemester_kurzbz)
    {
	$this->BewerbungstermineModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz);
	if($this->BewerbungstermineModel->isResultValid() === true)
	{
	    return $this->BewerbungstermineModel->result->retval;
	}
	else
	{
	    $this->_setError(true, $this->BewerbungstermineModel->getErrorMessage());
	}
    }
    
//    private function _checkRequiredFields()
//    {
//	if(($this->_isPersonalDataComplete()) && ($this->_isAdressDataComplete()) && ($this->_isKontaktDataComplete()) && ($this->_isDocumentDataComplete()))
//	{
//	    $this->_data["completed"] = true;
//	}
//	else
//	{
//	    $this->_data["completed"] = false;
//	}
//    }
//    
//    private function _isPersonalDataComplete()
//    {
//	$person = $this->_data["person"];
//	if($person->vorname == null)
//	{
//	    return false;
//	}
//	
//	if($person->nachname == null)
//	{
//	    return false;
//	}
//	
//	if($person->gebdatum == null)
//	{
//	    return false;
//	}
//	
//	if($person->gebort == null)
//	{
//	    return false;
//	}
//	
//	if($person->staatsbuergerschaft == null)
//	{
//	    return false;
//	}
//	
//	if($person->geburtsnation == null)
//	{
//	    return false;
//	}
//	
//	if($person->svnr == null)
//	{
//	    return false;
//	}
//	
//	if(($person->geschlecht != "m") && ($person->geschlecht != "w"))
//	{
//	    return false;
//	}
//	
//	return true;
//    }
//    
//    private function _isAdressDataComplete()
//    {
//	if(isset($this->_data["adresse"]))
//	{
//	    $adresse = $this->_data["adresse"];
//	    if($adresse->strasse == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->plz == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->ort == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->nation == null)
//	    {
//		return false;
//	    }
//	}
//	else
//	{
//	    return false;
//	}
//	
//	if(isset($this->_data["zustell_adresse"]))
//	{
//	    $adresse = $this->_data["zustell_adresse"];
//	    if($adresse->strasse == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->plz == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->ort == null)
//	    {
//		return false;
//	    }
//	    
//	    if($adresse->nation == null)
//	    {
//		return false;
//	    }
//	}
//	else
//	{
//	    return false;
//	}
//	return true;
//    }
//    
//    private function _isKontaktDataComplete()
//    {
//	if(isset($this->_data["kontakt"]["telefon"]))
//	{
//	    $kontakt = $this->_data["kontakt"]["telefon"];
//	    if($kontakt->kontakt == null)
//	    {
//		return false;
//	    }
//	}
//	else
//	{
//	    return false;
//	}
//	return true;
//    }
//    
//    private function _isDocumentDataComplete()
//    {
//	if(isset($this->_data["dokumente"]))
//	{
//	    $dokumente = $this->_data["dokumente"];
//	    if(!isset($dokumente["pass"]))
//	    {
//		return false;
//	    }
//	    
//	    if(!isset($dokumente["Lebenslf"]))
//	    {
//		return false;
//	    }
//	}
//	else
//	{
//	    return false;
//	}
//	
//	return true;
//    }
}
