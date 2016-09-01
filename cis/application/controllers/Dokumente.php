<?php
/**
 * ./cis/application/controllers/Dokumente.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Dokumente extends MY_Controller {

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
	 *
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->lang->load('dokumente', $this->get_language());
		$this->load->model('studiengang_model', "StudiengangModel");
		$this->load->model('studienplan_model', "StudienplanModel");
		$this->load->model('studiensemester_model', "StudiensemesterModel");
		$this->load->model('prestudent_model', "PrestudentModel");
		$this->load->model('prestudentStatus_model', "PrestudentStatusModel");
		$this->load->model('person_model', 'PersonModel');
		$this->load->model('DokumentStudiengang_model', "DokumentStudiengangModel");
		$this->load->model('akte_model', "AkteModel");
		$this->load->model('dms_model', "DmsModel");
		$this->load->helper("form");
	}


	/**
	 *
	 */
	public function index() {
		$this->checkLogin();

		$this->_data["sprache"] = $this->get_language();

		$this->_loadData();

		$this->_handleFileUpload();

		$this->load->view('dokumente', $this->_data);
	}


	/**
	 *
	 */
	private function _handleFileUpload() {
		$post = $this->input->post();
		$files = $_FILES;
		if (count($files) > 0) {
			foreach ($files as $key=>$file) {
				if (is_uploaded_file($file["tmp_name"])) {
					$obj = new stdClass();
					$obj->version = 0;
					$obj->mimetype = $file["type"];
					$obj->name = $file["name"];
					$obj->oe_kurzbz = null;
					$obj->dokument_kurzbz = $key;

					$akte = new stdClass();

					foreach ($this->_data["dokumente"] as $akte_temp)
					{
						if(($akte_temp->dokument_kurzbz == $obj->dokument_kurzbz) && ($obj->dokument_kurzbz != "Sonst"))
						{
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

					if(unlink($file["tmp_name"]))
					{
						//removing tmp file successful
					}
				}
				else
				{
					if(isset($post[$key."_nachgereicht"]) && (!isset($this->_data["dokumente"][$key])))
					{
						$akte = new stdClass();
						$akte->person_id = $this->_data["person"]->person_id;

						$akte->bezeichnung = $file["name"];
						$akte->dokument_kurzbz = $key;
						$akte->insertvon = 'online';
						$akte->nachgereicht = true;

						$this->_saveAkte($akte);
						//$this->AkteModel->saveAkte($akte);
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
	}


	/**
	 *
	 */
	private function _loadData()
	{
		//load studiensemester
		$this->_data["studiensemester"] = $this->_loadNextStudiensemester();

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);

		$this->_data["studiengaenge"] = array();
		foreach($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
			$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
			$studiengang->studienplan = $studienplan;
			$studiengang->dokumente = $this->_loadDokumentByStudiengang($prestudent->studiengang_kz);
			array_push($this->_data["studiengaenge"], $studiengang);
		}
	}


	/**
	 *
	 * @return unknown
	 */
	private function _loadNextStudiensemester()
	{
		$this->StudiensemesterModel->getNextStudiensemester("WS");
		if($this->StudiensemesterModel->isResultValid() === true)
		{
			return $this->StudiensemesterModel->result->retval[0];
		}
		else
		{
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
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


}
