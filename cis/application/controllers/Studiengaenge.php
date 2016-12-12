<?php

/**
 * ./cis/application/controllers/Studiengaenge.php
 *
 * @package default
 */
class Studiengaenge extends MY_Controller
{

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('studiengang_model', "StudiengangModel");
		$this->load->model('studiensemester_model', 'StudiensemesterModel');
		$this->load->model('organisationsform_model', 'OrgformModel');
		$this->load->model('person_model', 'PersonModel');
		$this->load->model('Bewerbungstermine_model', 'BewerbungstermineModel');
		$this->load->model('prestudent_model', "PrestudentModel");
		$this->load->model('prestudentStatus_model', "PrestudentStatusModel");
		$this->lang->load('studiengaenge', $this->get_language());
		$this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
	}

	/**
	 *
	 */
	public function index()
	{
		$this->benchmark->mark('code_start');
		$this->checkLogin();

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		if (isset($this->input->get()["studiengang_kz"]))
			$this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];

		$this->_data['title'] = 'Overview';
		$this->_data['sprache'] = $this->get_language();

		$this->OrgformModel->getAll();

		if ($this->OrgformModel->result->error == 0)
			$this->_data["orgform"] = $this->OrgformModel->result->retval;
		else
			$this->_setError(true, $this->OrgformModel->getErrorMessage());
		
		$studiensemester = $this->_getNextStudiensemester("WS");
		$this->session->set_userdata("studiensemester_kurzbz", $studiensemester->studiensemester_kurzbz);
		
		if (($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
		{
			$this->benchmark->mark('codepart_start');
			$this->_data["studiensemester"] = $studiensemester;
			//$this->_data["studiengaenge"] = $this->_getStudiengaengeStudienplan($this->_data["studiensemester"]->studiensemester_kurzbz, 1);
			$this->_data["studiengaenge"] = $this->_getStudiengaengeBewerbung();
			$this->benchmark->mark('codepart_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->getStudienplan: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

            $this->benchmark->mark('codepart_start');
			$bewerbungstermine = $this->_getBewerbungstermine();
            $this->benchmark->mark('codepart_end');
            log_message('debug', 'Time elapsed for Studiengaenge/index->getBewerbungstermine: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

            $this->benchmark->mark('foreach_start');
			foreach ($this->_data["studiengaenge"] as $stg)
			{
				if ($stg->onlinebewerbung === true)
				{
					$this->benchmark->mark('codepart_start');

					foreach ($stg->studienplaene as $key_studienplaene => $row_studienplaene)
					{
						$stg->studienplaene[$key_studienplaene]->fristen = array();
						foreach ($bewerbungstermine as $row_bewerbungstermin)
						{
							if ($row_studienplaene->studienplan_id == $row_bewerbungstermin->studienplan_id)
							{
								$stg->studienplaene[$key_studienplaene]->fristen[] = $row_bewerbungstermin;
							}
						}
					}
					$this->benchmark->mark('codepart_end');
					log_message('debug', 'Time elapsed for Studiengaenge/index->Reihunstest/Termin: ' . $this->benchmark->elapsed_time('codepart_start', 'codepart_end') . 'ms');

					if (isset($this->_data["studiengang_kz"]) && ($stg->studiengang_kz === $this->_data["studiengang_kz"]))
						if (count($stg->studienplaene) === 1)
							redirect("/Bewerbung/studiengang/" . $stg->studiengang_kz . "/" . $stg->studienplaene[0]->studienplan_id);
				}
			}
			$this->benchmark->mark('foreach_end');
			log_message('debug', 'Time elapsed for Studiengaenge/index->foreach: ' . $this->benchmark->elapsed_time('foreach_start', 'foreach_end') . 'ms');

			//load preinteressent data
			$this->_data["prestudent"] = $this->_loadPrestudent();
			$this->_data["aktiveBewerbungen"] = array();
			foreach ($this->_data["prestudent"] as $prestudent)
			{
				//load studiengaenge der prestudenten
				$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
				if ((!empty($prestudent->prestudentStatus)) && ($prestudent->prestudentStatus->status_kurzbz === "Interessent" || $prestudent->prestudentStatus->status_kurzbz === "Bewerber"))
				{
					$this->_data["aktiveBewerbungen"][$prestudent->studiengang_kz] = $prestudent->prestudentStatus->studienplan_id;
				}
			}

            $this->benchmark->mark('load_view_start');
			$this->load->view('studiengaenge', $this->_data);
            $this->benchmark->mark('load_view_end');
            log_message('debug', 'Time elapsed for Studiengaenge/index->loadView: ' . $this->benchmark->elapsed_time('load_view_start', 'load_view_end') . 'ms');
		}
		else
		{
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
		}

		$this->benchmark->mark('code_end');
		log_message('debug', 'Time elapsed for Studiengaenge/index(): ' . $this->benchmark->elapsed_time('code_start', 'code_end') . 'ms');
	}

	/**
	 *
	 * @return unknown
	 */
	private function _loadPerson()
	{
		$this->PersonModel->getPersonen(array("person_id" => $this->session->userdata()["person_id"]));
		if ($this->PersonModel->isResultValid() === true)
		{
			if (count($this->PersonModel->result->retval) == 1)
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

	/**
	 *
	 * @param unknown $art
	 * @return unknown
	 */
	private function _getNextStudiensemester($art)
	{
		$this->StudiensemesterModel->getNextStudiensemester($art);
		if ($this->StudiensemesterModel->isResultValid() === true)
			return $this->StudiensemesterModel->result->retval[0];
		else
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
	}

	private function _getStudiengaengeStudienplan($studiensemester_kurzbz, $ausbildungssemester)
	{
		$this->StudiengangModel->getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester);
		if ($this->StudiengangModel->isResultValid() === true)
			return $this->StudiengangModel->result->retval;
		else
			$this->_setError(true, $this->StudiengangModel->getErrorMessage());
	}

	private function _getStudiengaengeBewerbung()
	{
		$this->StudiengangModel->getStudiengangBewerbung();
		if ($this->StudiengangModel->isResultValid() === true)
			return $this->StudiengangModel->result->retval;
		else
			$this->_setError(true, $this->StudiengangModel->getErrorMessage());
	}

	private function _getBewerbungstermine()
	{
		$this->BewerbungstermineModel->getCurrent();
		if ($this->BewerbungstermineModel->isResultValid() === true)
		{
			return $this->BewerbungstermineModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->BewerbungstermineModel->getErrorMessage());
		}
	}

	private function _loadPrestudent()
	{
		$this->PrestudentModel->getPrestudent(array("person_id" => $this->session->userdata()["person_id"]));
		if ($this->PrestudentModel->isResultValid() === true)
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
		//$this->PrestudentStatusModel->getLastStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1));
		$this->PrestudentStatusModel->getLastStatus(array("prestudent_id" => $prestudent_id, "studiensemester_kurzbz" => $this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester" => 1));
		if ($this->PrestudentStatusModel->isResultValid() === true)
		{
			if (($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
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

}
