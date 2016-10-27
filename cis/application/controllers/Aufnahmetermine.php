<?php
/**
 * ./cis/application/controllers/Aufnahmetermine.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

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
	 *
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
		$this->load->model('studiengangstyp_model', 'StudiengangstypModel');
		$this->load->model('message_model', 'MessageModel');
		$this->load->helper("form");
		$this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
	}


	/**
	 *
	 */
	public function index() {
		$this->checkLogin();
		
		//workaround for inserting code for Google Tag Manager
		if(isset($this->input->get()["send"]))
		{
			$time = time();
			if(!(($time - $this->input->get()["send"]) > 5))
			{
				$this->_data["gtm"] = true;
			}
		}

		$this->_data["sprache"] = $this->get_language();

		$this->_loadData();

		$this->load->view('aufnahmetermine', $this->_data);
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @param unknown $studienplan_id
	 */
	public function register($studiengang_kz, $studienplan_id) {
		$this->checkLogin();

		$this->_data["sprache"] = $this->get_language();

		$reihungstest = $this->_loadReihungstest($this->input->post()["rtTermin"]);

		$this->_loadData();

		if (date("Y-m-d", strtotime($reihungstest->anmeldefrist)) > date("Y-m-d")) {
			//check if new registration or change
			if (!empty($this->_data["anmeldungen"])) {
				foreach ($this->_data["anmeldungen"] as $anmeldung) {
					if (($anmeldung->studiengang_kz === $studiengang_kz) && ($anmeldung->reihungstest_id !== $this->input->post()["rtTermin"]))
					{
						$this->_deleteRegistrationToReihungstest($anmeldung);
						$this->_registerToReihungstest($this->session->userdata()["person_id"], $this->input->post()["rtTermin"], $studienplan_id);
						foreach($this->_data["studiengaenge"] as $studiengang)
						{
							if($studiengang->studiengang_kz === $studiengang_kz)
							{
								$studiengang->studiengangstyp = $this->_loadStudiengangstyp($studiengang->typ);
								$this->_sendMessageMailAppointmentConfirmation($this->_data["person"], $studiengang, $reihungstest);
							}
						}
					}
				}
			}
			else
			{
				$this->_registerToReihungstest($this->session->userdata()["person_id"], $this->input->post()["rtTermin"], $studienplan_id);
				foreach($this->_data["studiengaenge"] as $studiengang)
				{
					if($studiengang->studiengang_kz === $studiengang_kz)
					{
						$studiengang->studiengangstyp = $this->_loadStudiengangstyp($studiengang->typ);
						$this->_sendMessageMailAppointmentConfirmation($this->_data["person"], $studiengang, $reihungstest);
					}
				}
			}
			$this->_loadData();
		}
		else
		{
			$this->_data["anmeldeMessage"] = $this->getPhrase("Test/FristAbgelaufen", $this->_data["sprache"], $this->config->item('root_oe'));
		}

		$this->load->view('aufnahmetermine', $this->_data);
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

		$this->_data["anmeldungen"] = $this->_loadReihungstestsByPersonId($this->_data["person"]->person_id);

		$this->_data["rt_person"] = array();
		foreach($this->_data["anmeldungen"] as $anmeldung)
		{
			$this->_data["rt_person"][$anmeldung->studiengang_kz] = $anmeldung->reihungstest_id;
		}

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();

		$this->_data["studiengaenge"] = array();
		$this->_data["reihungstests"] = array();
		foreach($this->_data["prestudent"] as $prestudent)
		{
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

			if((!empty($prestudent->prestudentStatus)) && ($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null))
			{
				//load studiengaenge der prestudenten
				$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;
				array_push($this->_data["studiengaenge"], $studiengang);

				$reihungstests = $this->_loadReihungstests($prestudent->studiengang_kz, $this->_data["studiensemester"]->studiensemester_kurzbz);
				if(!empty($reihungstests))
				{
					$this->_data["reihungstests"][$prestudent->studiengang_kz] = array();
					foreach($reihungstests as $rt)
					{
						if(isset($rt->stufe) && ($rt->stufe <= $prestudent->prestudentStatus->rt_stufe))
						{
							$this->_data["reihungstests"][$prestudent->studiengang_kz][$rt->stufe][$rt->reihungstest_id] = date("d.m.Y", strtotime($rt->datum))." // ".$this->getPhrase("Test/Bewerbungsfrist", $this->_data["sprache"], $this->config->item('root_oe'))." ".date("d.m.Y", strtotime($rt->anmeldefrist));
						}
					}
				}
			}
		}
	}


	/**
	 *
	 * @param unknown $person_id
	 * @param unknown $reihungstest_id
	 * @param unknown $studienplan_id
	 */
	private function _registerToReihungstest($person_id, $reihungstest_id, $studienplan_id)
	{
		$this->PrestudentModel->registerToReihungstest($person_id, $reihungstest_id, $studienplan_id);
		if($this->PrestudentModel->isResultValid() === true)
		{

		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}


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


	private function _loadReihungstests($studiengang_kz, $studiensemester_kurzbz=null)
	{
		$this->ReihungstestModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz);
		if($this->ReihungstestModel->isResultValid() === true)
		{
			return $this->ReihungstestModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->ReihungstestModel->getErrorMessage());
		}
	}


	private function _loadReihungstestsByPersonId($person_id)
	{
		$this->ReihungstestModel->getReihungstestByPersonID($person_id);
		if($this->ReihungstestModel->isResultValid() === true)
		{
			return $this->ReihungstestModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->ReihungstestModel->getErrorMessage());
		}
	}


	private function _loadReihungstest($reihungstest_id)
	{
		$this->ReihungstestModel->getReihungstest($reihungstest_id);
		if($this->ReihungstestModel->isResultValid() === true)
		{
			return $this->ReihungstestModel->result->retval[0];
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
		//$this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
		$this->PrestudentStatusModel->getLastStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
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


	private function _deleteRegistrationToReihungstest($anmeldung)
	{
		$reihungstest = new stdClass();
		$reihungstest->person_id = $anmeldung->person_id;
		$reihungstest->rt_person_id = $anmeldung->rt_person_id;
		$reihungstest->rt_id = $anmeldung->rt_id;

		$this->PrestudentModel->deleteRegistrationToReihungstest($reihungstest);
		if($this->PrestudentModel->isResultValid() === true)
		{

		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}


	private function _sendMessageMailAppointmentConfirmation($person, $studiengang, $termin)
	{
		$data = array(
			"typ" => $studiengang->studiengangstyp->bezeichnung,
			"studiengang" => $studiengang->bezeichnung,
			"orgform" => $studiengang->orgform_kurzbz,
			"termin" => date("d.m.Y", strtotime($termin->datum))." ".date("H:i", strtotime($termin->uhrzeit))
		);

		$oe = $studiengang->oe_kurzbz;
		$orgform_kurzbz = $studiengang->orgform_kurzbz;

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->_data["sprache"];

		$this->MessageModel->sendMessageVorlage("MailAppointmentConfirmation", $oe, $data, $sprache, $orgform_kurzbz=null, null, $person->person_id);

		// var_dump($this->MessageModel->result);

		if($this->MessageModel->isResultValid() === true)
		{
			if((isset($this->MessageModel->result->error)) && ($this->MessageModel->result->error === 0))
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


}
