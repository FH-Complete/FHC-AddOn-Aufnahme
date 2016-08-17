<?php

class Send extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('send', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        $this->load->model('person_model', 'PersonModel');
	$this->load->model('studiengangstyp_model', 'StudiengangstypModel');
	$this->load->model('message_model', 'MessageModel');
        $this->load->helper("form");
        $this->load->library("form_validation");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
	$this->_loadLanguage($this->_data["sprache"]);
        
        if(isset($this->input->get()["studiengang_kz"]))
        {   
	    //load person data
	    $this->_data["person"] = $this->_loadPerson();
	    
            //load studiengang
            $this->_data["studiengang"] = $this->_loadStudiengang($this->input->get()["studiengang_kz"]);
	    
	    $this->_data["studiengang"]->studiengangstyp = $this->_loadStudiengangstyp($this->_data["studiengang"]->typ);
	    
	    $this->_sendMessageMailApplicationConfirmation($this->_data["person"], $this->_data["studiengang"]);
	    //TODO vorlage fehlt in DB
	    $this->_sendMessageMailNewApplicationInfo($this->_data["person"], $this->_data["studiengang"]);
	    
	    
            //load preinteressent data
            $this->_data["prestudent"] = $this->_loadPrestudent();

            //load prestudent data for correct studiengang
            foreach($this->_data["prestudent"] as $prestudent)
            {
                //load studiengaenge der prestudenten
                if($prestudent->studiengang_kz == $this->input->get()["studiengang_kz"])
                {
                    $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                    $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);         
                    $this->_data["studiengang"]->studienplan = $studienplan;
		    $this->_data["prestudentStatus"] = $prestudent->prestudentStatus;
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
	
	//load person data
        $this->_data["person"] = $this->_loadPerson();
        
        //load studiengang
        $this->_data["studiengang"] = $this->_loadStudiengang($studiengang_kz);
	
        $this->_data["prestudent"] = $this->_loadPrestudent();
        
        //load prestudent data for correct studiengang
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            if($prestudent->studiengang_kz == $studiengang_kz)
            {
                $prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                $studienplan = $this->_loadStudienplan($prestudentStatus->studienplan_id);         
                $this->_data["studiengang"]->studienplan = $studienplan;
		
                //TODO check if status exists
                if(is_null($prestudentStatus->bewerbung_abgeschicktamum))
                {
                    $prestudentStatus->bewerbung_abgeschicktamum=date('Y-m-d H:i:s');
                    $this->_savePrestudentStatus($prestudentStatus);
		    
                    
                    //TODO send mails
                }
                else
                {
                    //TODO bewerbung bereits abgeschickt;
                }
		$this->_data["prestudentStatus"] = $prestudentStatus;

                //$this->load->view('send', $this->_data);
		redirect("/Aufnahmetermine");
            } 
        }
    }
    
    private function _loadStudiengang($studiengang_kz)
    {
	$this->StudiengangModel->getStudiengang($studiengang_kz);
	if($this->StudiengangModel->isResultValid() === true)
	{
	    return $this->StudiengangModel->result->retval[0];
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
	    return $this->StudienplanModel->result->retval[0];
	}
	else
	{
	    $this->_setError(true, $this->StudienplanModel->getErrorMessage());
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
	    return $this->PrestudentStatusModel->result->retval[0];
	}
	else
	{
	    $this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
	}
    }
    
    private function _savePrestudentStatus($prestudentStatus)
    {	
	$this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
	if($this->PrestudentStatusModel->isResultValid() === true)
	{
	    //TODO Daten erfolgreich gespeichert
	}
	else
	{
	    $this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
	}
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
    
    private function _loadStudiengangstyp($typ)
    {
	$this->StudiengangstypModel->get($typ);
	if($this->StudiengangstypModel->isResultValid() === true)
	{
	    return $this->StudiengangstypModel->result->retval[0];
	}
	else
	{
	    $this->_setError(true, $this->StudiengangstypModel->getErrorMessage());
	}
    }
    
    private function _sendMessageMailApplicationConfirmation($person, $studiengang)
    {
	$data = array(
	    "anrede" => (is_null($person->anrede)) ? "" : $person->anrede,
	    "vorname" => $person->vorname,
	    "nachname" => $person->nachname,
	    "typ" => $studiengang->studiengangstyp->bezeichnung,
	    "studiengang" => $studiengang->bezeichnung
	);
	
	$oe = $studiengang->oe_kurzbz;
	$orgform_kurzbz = $studiengang->orgform_kurzbz;

	(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->_data["sprache"];
	
	$this->MessageModel->sendMessageVorlage($this->config->item("systemPersonId"), $person->person_id, "MailApplicationConfirmation", $oe, $data, $sprache, $orgform_kurzbz=null);
	
	if($this->MessageModel->isResultValid() === true)
	{
	    if($this->MessageModel->result->msg === "Success")
	    {
		//success
	    }
	    else
	    {
		$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />';
	    }
	}
	else
	{
	    $this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />';
	    $this->_setError(true, $this->MessageModel->getErrorMessage());
	}
    }
    
    private function _sendMessageMailNewApplicationInfo($person, $studiengang)
    {
	$data = array(
	    "anrede" => (is_null($person->anrede)) ? "" : $person->anrede,
	    "vorname" => $person->vorname,
	    "nachname" => $person->nachname,
	    "typ" => $studiengang->studiengangstyp->bezeichnung,
	    "studiengang" => $studiengang->bezeichnung
	);
	
	$oe = $studiengang->oe_kurzbz;
	$orgform_kurzbz = $studiengang->orgform_kurzbz;

	(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->_data["sprache"];
	
	$this->MessageModel->sendMessageVorlage($person->person_id, $this->config->item("systemPersonId"), "MailNewApplicationInfo", $oe, $data, $sprache, $orgform_kurzbz);

//	var_dump($this->MessageModel->result);
	
	if($this->MessageModel->isResultValid() === true)
	{
	    if($this->MessageModel->result->msg === "Success")
	    {
		//success
	    }
	    else
	    {
		$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />';
	    }
	}
	else
	{
	    $this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />';
	    $this->_setError(true, $this->MessageModel->getErrorMessage());
	}
    }
}
