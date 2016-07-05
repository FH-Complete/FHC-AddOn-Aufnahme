<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aufnahmetermine extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *    http://example.com/index.php/welcome
     *  - or -
     *    http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->lang->load('termine', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('studiensemester_model', "StudiensemesterModel");
	$this->load->model('reihungstest_model', "ReihungstestModel");
	$this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
	$this->load->model('person_model', 'PersonModel');
	$this->load->model('studienplan_model', "StudienplanModel");
	$this->load->helper("form");
    }

    public function index() {
        $this->checkLogin();
	
	$this->_data["sprache"] = $this->get_language();
        
        //load studiensemester
        $this->_data["studiensemester"] = $this->_loadNextStudiensemester();
	
	//load person data
        $this->_data["person"] = $this->_loadPerson();
	
	//load preinteressent data
        $this->_data["prestudent"] = $this->_loadPrestudent();
	
	$this->_data["studiengaenge"] = array();
	$this->_data["reihungstests"] = array();
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
            $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
            $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
            $studiengang->studienplan = $studienplan;
	    array_push($this->_data["studiengaenge"], $studiengang);
	    //TODO set stgkz and studiensemester_kurzbz
	    $reihungstests = $this->_loadReihungstests($prestudent->studiengang_kz, $this->_data["studiensemester"]->studiensemester_kurzbz);
	    if(!empty($reihungstests))
	    {
		$this->_data["reihungstests"][$prestudent->studiengang_kz] = array();
		foreach($reihungstests as $rt)
		{
		    $this->_data["reihungstests"][$prestudent->studiengang_kz][$rt->stufe][] = $rt;
		}
	    }
        }
	
        $this->load->view('aufnahmetermine', $this->_data);
    }
    
    public function register()
    {
	$this->checkLogin();
	
	$this->_data["sprache"] = $this->get_language();
	
	var_dump($this->input->post());
    }
    
    private function _loadNextStudiensemester()
    {
	$this->StudiensemesterModel->getNextStudiensemester("WS");
	if($this->StudiensemesterModel->isResultValid() == true)
	{
	    return $this->StudiensemesterModel->result->retval[0];
	}
	else
	{
	    $this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
	}
    }
    
    private function _loadReihungstests($studiengang_kz, $studiensemester_kurzbz=null)
    {
	$this->ReihungstestModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz);
	if($this->ReihungstestModel->isResultValid() == true)
	{
	    return $this->ReihungstestModel->result->retval;
	}
	else
	{
	    $this->_setError(true, $this->ReihungstestModel->getErrorMessage());
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
}
