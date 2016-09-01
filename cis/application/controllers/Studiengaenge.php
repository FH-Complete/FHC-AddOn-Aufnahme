<?php

class Studiengaenge extends MY_Controller {

    public function __construct()
	{
        parent::__construct();
        $this->load->model('studiengang_model', "StudiengangModel");
		//$this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisationsform_model', 'OrgformModel');
		$this->load->model('person_model', 'PersonModel');
		$this->load->model('Bewerbungstermine_model', 'BewerbungstermineModel');
		//$this->load->model('reihungstest_model', "ReihungstestModel");
        $this->lang->load('studiengaenge', $this->get_language());
    }

    public function index()
    {
		$this->benchmark->mark('code_start');
		$this->checkLogin();

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		if(isset($this->input->get()["studiengang_kz"]))
			$this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];

        $this->_data['title'] = 'Overview';
        $this->_data['sprache'] = $this->get_language();

        $this->OrgformModel->getAll();

        if($this->OrgformModel->result->error == 0)
            $this->_data["orgform"] = $this->OrgformModel->result->retval;
        else
	    	$this->_setError(true, $this->OrgformModel->getErrorMessage());

	    $studiensemester = $this->_getNextStudiensemester("WS");

	    if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
	    {
			$this->benchmark->mark('codepart_start');
			$this->_data["studiensemester"] = $studiensemester;
			$this->_data["studiengaenge"] = $this->_getStudiengaengeStudienplan($this->_data["studiensemester"]->studiensemester_kurzbz, 1);
			$this->benchmark->mark('codepart_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->getStudienplan: '.$this->benchmark->elapsed_time('codepart_start', 'codepart_end').'ms');

			$this->benchmark->mark('foreach_start');
			foreach($this->_data["studiengaenge"] as $stg)
			{
				if($stg->onlinebewerbung === "t")
				{
					$this->benchmark->mark('codepart_start');
					$stg->fristen = $this->_getBewerbungstermine($stg->studiengang_kz, $this->_data["studiensemester"]->studiensemester_kurzbz);
					//$stg->reihungstests = $this->_loadReihungstests($stg->studiengang_kz, $this->_data["studiensemester"]->studiensemester_kurzbz);
					$this->benchmark->mark('codepart_end');
					log_message('debug', 'Time elapsed for Studiengaenge/index->Reihunstest/Termin: '.$this->benchmark->elapsed_time('codepart_start', 'codepart_end').'ms');

					if(isset($this->_data["studiengang_kz"]) && ($stg->studiengang_kz === $this->_data["studiengang_kz"]))
						if(count($stg->studienplaene) === 1)
							redirect("/Bewerbung/studiengang/".$stg->studiengang_kz."/".$stg->studienplaene[0]->studienplan_id);
				}
			}
			$this->benchmark->mark('foreach_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->foreach: '.$this->benchmark->elapsed_time('foreach_start', 'foreach_end').'ms');

			$this->load->view('studiengaenge', $this->_data);
	    }
	    else
	    {
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());

	    }

	//$this->load->view('studiengaenge', $this->_data);
	$this->benchmark->mark('code_end');
	log_message('debug', 'Time elapsed for Studiengaenge/index(): '.$this->benchmark->elapsed_time('code_start', 'code_end').'ms');
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
			return $this->StudiensemesterModel->result->retval[0];
		else
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
    }

    private function _getStudiengaengeStudienplan($studiensemester_kurzbz, $ausbildungssemester)
    {
		$this->StudiengangModel->getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester);
		if($this->StudiengangModel->isResultValid() === true)
			return $this->StudiengangModel->result->retval;
		else
			$this->_setError(true, $this->StudiengangModel->getErrorMessage());
    }

//    private function _getCompleteStudiengang($studiensemester_kurzbz, $ausbildungssemester)
//    {
//	$this->StudiengangModel->getCompleteStudiengang($studiensemester_kurzbz, $ausbildungssemester);
//	if($this->StudiengangModel->isResultValid() === true)
//	{
//	    return $this->StudiengangModel->result->retval;
//	}
//	else
//	{
//	    $this->_setError(true, $this->StudiengangModel->getErrorMessage());
//	}
//    }

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

//    private function _loadReihungstests($studiengang_kz, $studiensemester_kurzbz=null)
//    {
//	$this->ReihungstestModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz);
//	if($this->ReihungstestModel->isResultValid() === true)
//	{
//	    return $this->ReihungstestModel->result->retval;
//	}
//	else
//	{
//	    $this->_setError(true, $this->ReihungstestModel->getErrorMessage());
//	}
//    }
}
