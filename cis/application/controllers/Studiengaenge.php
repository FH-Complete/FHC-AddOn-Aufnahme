<?php

class Studiengaenge extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisationsform_model', 'OrgformModel');
	$this->load->model('person_model', 'PersonModel');
        $this->lang->load('studiengaenge', $this->get_language());
    }

    public function index() 
    {
        $this->checkLogin();
	
	//load person data
        $this->_data["person"] = $this->_loadPerson();
        
        if(isset($this->input->get()["studiengang_kz"]))
        {
            $this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];
        }
        
        $this->_data['title'] = 'Overview';
        $this->_data['sprache'] = $this->get_language();
	
	
	
        $this->OrgformModel->getAll();
        
        if($this->OrgformModel->result->error == 0)
        {
            $this->_data["orgform"] = $this->OrgformModel->result->retval;
        }
        else
        {
            //TODO error while loading orgform
        }
        
	$studiensemester = $this->_getNextStudiensemester("WS");
	
        if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
        {
            $this->_data["studiensemester"] = $studiensemester;
//            $this->StudiengangModel->getAll();
//        
//            if($this->StudiengangModel->result->error == 0)
//            {
//                foreach($this->StudiengangModel->result->retval as $key=>$studiengang)
//                {
//                    $this->StudienplanModel->getStudienplaeneFromSem(array(
//                                "studiengang_kz"=>$studiengang->studiengang_kz,
//                                "studiensemester_kurzbz"=>$this->_data["studiensemester"]->studiensemester_kurzbz,
//                                "ausbildungssemester"=>1
//                        ));
//                    
//                    if($this->StudienplanModel->result->error == 0)
//                    {
//                        $studiengang->studienplaene = $this->StudienplanModel->result->retval;
//                    }
//		    else
//		    {
//			$this->_setError(true, $this->StudienplanModel->getErrorMessage());
//		    }
//                }
//                $this->_data["studiengaenge"] = $this->StudiengangModel->result->retval;
//            }
//            else
//            {
//                //TODO could not load data
//            }
	    
	    $this->_data["studiengaenge"] = $this->_getStudiengaengeStudienplan($this->_data["studiensemester"]->studiensemester_kurzbz, 1);
	    
            $this->load->view('studiengaenge', $this->_data);
        }
        else
        {
            //TODO studiensemester not found
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
    
    private function _getNextStudiensemester($art)
    {
	$this->StudiensemesterModel->getNextStudiensemester($art);
	if($this->StudiensemesterModel->isResultValid() === true)
	{
	    return $this->StudiensemesterModel->result->retval[0];
	}
	else
	{
	    $this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
	}
    }
    
    private function _getStudiengaengeStudienplan($studiensemester_kurzbz, $ausbildungssemester)
    {
	$this->StudiengangModel->getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester);
	if($this->StudiengangModel->isResultValid() === true)
	{
	    return $this->StudiengangModel->result->retval;
	}
	else
	{
	    $this->_setError(true, $this->StudiengangModel->getErrorMessage());
	}
    }
}
