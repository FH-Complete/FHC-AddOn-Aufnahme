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
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        $this->load->model('studienplan_model', "StudienplanModel");
	$this->load->model('dms_model', "DmsModel");
        $this->load->model('akte_model', "AkteModel");
	$this->load->model('DokumentStudiengang_model', "DokumentStudiengangModel");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        //load studiengang
        $this->_loadStudiengang($this->input->get()["studiengang_kz"]);
	
	//load Dokumente from Studiengang
	$this->_data["dokumenteStudiengang"] = $this->_loadDokumentByStudiengang($this->input->get()["studiengang_kz"]);
        
        //load nationen
        $this->_loadNationen();
        
        //load bundeslaender
        $this->_loadBundeslaender();
        
        //load person
        $this->_loadPerson();
        
        //load adresse
        $this->_loadAdresse();
        
        //load kontakt
        $this->_loadKontakt();
        
        //load prestudent
        $this->_data["prestudent"] = $this->_loadPrestudent();
        
        //load studiengang
        foreach($this->_data["prestudent"] as $prestudent)
        {
            if($prestudent->studiengang_kz == $this->input->get()["studiengang_kz"])
            {
                $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);         
                $this->_data["studiengang"]->studienplan = $studienplan;
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
	
        $this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"]));
	if($this->PersonModel->isResultValid() === true)
        {
            if(count($this->PersonModel->result->retval) == 1)
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
    
    private function _loadNationen()
    {
	$this->NationModel->getNationen();
        if($this->NationModel->isResultValid() === true)
        {
            $this->_data["nationen"] = $this->NationModel->result->retval;        
        }
	else
	{
	    $this->_setError(true, $this->NationModel->getErrorMessage());
	}
    }
    
    private function _loadBundeslaender()
    {
	$this->BundeslandModel->getBundeslaender();
        if($this->BundeslandModel->isResultValid() === true)
        {
	    $this->_data["bundeslaender"] = $this->BundeslandModel->result->retval;        
        }
	else
	{
	    $this->_setError(true, $this->BundeslandModel->getErrorMessage());
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
	    $this->_setError(true, $this->PrestudentModel->getErrorMessage());
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
    
    private function _loadDokumentByStudiengang($studiengang_kz)
    {
	$this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($studiengang_kz, true, true);
        if($this->DokumentStudiengangModel->isResultValid() === true)
        {
	    return $this->DokumentStudiengangModel->result->retval;
        }
	else
	{
	    $this->_setError(true, $this->DokumentStudiengangModel->getErrorMessage());
	}
    }
}
