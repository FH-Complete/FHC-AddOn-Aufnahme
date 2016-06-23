<?php

class Send extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('send', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
	$this->load->model('phrase_model', 'PhraseModel');
        
        $this->load->helper("form");
        $this->load->library("form_validation");
    }

    public function index() {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
        
        if(isset($this->input->get()["studiengang_kz"]))
        {           
            //load studiengang
            $this->_data["studiengang"] = $this->_loadStudiengang($this->input->get()["studiengang_kz"]);
	    
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
	    $this->_getPhrasen($this->_data["sprache"], $this->_data["studiengang"]->oe_kurzbz, $this->_data["studiengang"]->studienplan->orgform_kurzbz);
            
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

                $this->load->view('send', $this->_data);
            } 
        }
    }
    
    private function _loadStudiengang($studiengang_kz)
    {
	$this->StudiengangModel->getStudiengang($studiengang_kz);
	if($this->StudiengangModel->isResultValid() == true)
	{
	    return $this->StudiengangModel->result->retval[0];
	}
	else
	{
	    //TODO
	    var_dump($this->StudiengangModel->getErrorMessage());
	}
    }
    
    private function _loadStudienplan($studienplan_id)
    {	
	$this->StudienplanModel->getStudienplan($studienplan_id);
	if($this->StudienplanModel->isResultValid() == true)
	{
	    return $this->StudienplanModel->result->retval[0];
	}
	else
	{
	    //TODO
	    var_dump($this->StudienplanModel->getErrorMessage());
	}
    }
    
    private function _loadPrestudent()
    {
	$this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"]));
	if($this->PrestudentModel->isResultValid() == true)
	{
	    return $this->PrestudentModel->result->retval;
	}
	else
	{
	    //TODO
	    var_dump($this->PrestudentModel->getErrorMessage());
	}
    }
    
    private function _loadPrestudentStatus($prestudent_id)
    {	
	$this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
	if($this->PrestudentStatusModel->isResultValid() == true)
	{
	    return $this->PrestudentStatusModel->result->retval[0];
	}
	else
	{
	    //TODO
	    var_dump($this->PrestudentStatusModel->getErrorMessage());
	}
    }
    
    private function _savePrestudentStatus($prestudentStatus)
    {	
	$this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
	if($this->PrestudentStatusModel->isResultValid() == true)
	{
	    //TODO Daten erfolgreich gespeichert
	}
	else
	{
	    //TODO
	    var_dump($this->PrestudentStatusModel->getErrorMessage());
	}
	
    }
    
    private function _getPhrasen($language, $oe_kurzbz, $orgform_kurzbz)
    {
	$this->PhraseModel->getPhrasen(ucfirst($language), $oe_kurzbz, $orgform_kurzbz);
	if($this->PhraseModel->isResultValid() == true)
	{
	    //TODO Phrasen loaded
	}
	else
	{
	    //TODO
	    var_dump($this->PhraseModel->getErrorMessage());
	}
    }
    
    private function getPhrase($phrase)
    {
	return $this->PhraseModel->getLoadedPhrase($phrase);
    }
}
