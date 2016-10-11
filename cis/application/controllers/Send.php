<?php
/**
 * ./cis/application/controllers/Send.php
 *
 * @package default
 */


class Send extends MY_Controller {

	/**
	 *
	 */
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
		$this->load->model('adresse_model', "AdresseModel");
		$this->load->model('kontakt_model', 'KontaktModel');
		$this->load->model('dms_model', "DmsModel");
		$this->load->model('akte_model', "AkteModel");
		$this->load->model('DokumentStudiengang_model', "DokumentStudiengangModel");
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
	}


	/**
	 *
	 */
	public function index()
	{
		$this->checkLogin();
		$this->_data['sprache'] = $this->get_language();
		$this->_loadLanguage($this->_data["sprache"]);

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		if($this->input->get("studiengang_kz") != null)
		{
			$this->_data["studiengang_kz"] = $this->input->get("studiengang_kz");
		}

		//load studiengang
		$this->_data["studiengang"] = $this->_loadStudiengang($this->input->get()["studiengang_kz"]);

		$this->_data["studiengang"]->studiengangstyp = $this->_loadStudiengangstyp($this->_data["studiengang"]->typ);

		$this->_loadAdresse();

		//load kontakt data
		$this->_loadKontakt();
		
		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();
		
		$this->_data["studiengaenge"] = array();
		$this->_data["prestudentStatus"] = array();
		foreach ($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
			$this->_data["prestudentStatus"][$prestudent->studiengang_kz] = $prestudent->prestudentStatus;

			if ((!empty($prestudent->prestudentStatus))
				&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
					|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber")) {
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;
				
				if($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null)
				{
					$this->_data["bewerbung_abgeschickt"] = true;
				}
				array_push($this->_data["studiengaenge"], $studiengang);
			}
		}

		//load Dokumente from Studiengang
		$this->_data["dokumenteStudiengang"] = array();
		foreach($this->_data["studiengaenge"] as $stg)
		{
			$this->_data["dokumenteStudiengang"][$stg->studiengang_kz] = $this->_loadDokumentByStudiengang($stg->studiengang_kz);
		}

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);

		$this->_data["completenessError"] = $this->_checkDataCompleteness();

		$this->load->view('send', $this->_data);
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @param unknown $studienplan_id
	 */
	public function send($studiengang_kz, $studienplan_id) {
		$this->checkLogin();
		$this->_data['sprache'] = $this->get_language();
		
		$this->_data["studiengang_kz"] = $studiengang_kz;

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load studiengang
		$this->_data["studiengang"] = $this->_loadStudiengang($studiengang_kz);

		$this->_data["studiengang"]->studiengangstyp = $this->_loadStudiengangstyp($this->_data["studiengang"]->typ);

		$this->_loadAdresse();

		//load kontakt data
		$this->_loadKontakt();

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();
		
		$this->_data["studiengaenge"] = array();
		$this->_data["prestudentStatus"] = array();
		foreach ($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
			$this->_data["prestudentStatus"][$prestudent->studiengang_kz] = $prestudent->prestudentStatus;

			if ((!empty($prestudent->prestudentStatus))
				&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
					|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber")) {
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;
				
				if($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null)
				{
					$this->_data["bewerbung_abgeschickt"] = true;
				}
				array_push($this->_data["studiengaenge"], $studiengang);
			}
		}

		//load Dokumente from Studiengang
		$this->_data["dokumenteStudiengang"] = array();
		foreach($this->_data["studiengaenge"] as $stg)
		{
			$this->_data["dokumenteStudiengang"][$stg->studiengang_kz] = $this->_loadDokumentByStudiengang($stg->studiengang_kz);
		}

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);
		
		$this->_data["completenessError"] = $this->_checkDataCompleteness();

		//load prestudent data for correct studiengang
		foreach ($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			if($prestudent->studiengang_kz == $studiengang_kz)
			{
				$prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
				$studienplan = $this->_loadStudienplan($prestudentStatus->studienplan_id);
				$this->_data["studiengang"]->studienplan = $studienplan;
//				$this->_data["prestudentStatus"] = $prestudentStatus;

				if((!empty($this->_data["completenessError"]["person"]))
						|| (!empty($this->_data["completenessError"]["adresse"]))
						|| (!empty($this->_data["completenessError"]["kontakt"]))
						|| (!empty($this->_data["completenessError"]["dokumente"][$prestudent->studiengang_kz]))
						|| (!empty($this->_data["completenessError"]["doks"])))
				{
					$this->_setError(true, $this->lang->line("send_datenUnvollstaendig"));
					$this->load->view('send', $this->_data);
				}
				else
				{
					if(is_null($prestudentStatus->bewerbung_abgeschicktamum))
					{
						$prestudentStatus->bewerbung_abgeschicktamum=date('Y-m-d H:i:s');
						unset($prestudentStatus->studienplan_bezeichnung);
						unset($prestudentStatus->bezeichnung_mehrsprachig);
						
						$this->_savePrestudentStatus($prestudentStatus);
						$this->_sendMessageMailApplicationConfirmation($this->_data["person"], $this->_data["studiengang"]);
						//TODO vorlage fehlt in DB
						$this->_sendMessageMailNewApplicationInfo($this->_data["person"], $this->_data["studiengang"]);
						redirect("/Aufnahmetermine");
					}
					else
					{
						$this->_setError(true, $this->lang->line("send_bereitsAbgeschickt"));
						$this->load->view('send', $this->_data);
					}
				}
			}
		}
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @return unknown
	 */
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
		//$this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
		$this->PrestudentStatusModel->getLastStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent"));
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

		$this->MessageModel->sendMessageVorlage("MailApplicationConfirmation", $oe, $data, $sprache, $orgform_kurzbz=null, null, $person->person_id);

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

		$this->MessageModel->sendMessageVorlage("MailNewApplicationInfo", $oe, $data, $sprache, $orgform_kurzbz, $person->person_id, null);

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


	private function _checkDataCompleteness()
	{
		$error = array("dokumente"=>array(), "person"=>array(), "adresse"=>array(), "kontakt"=>array(), "doks"=>array());
		
		//check documents
		foreach($this->_data["dokumenteStudiengang"] as $key=>$doks)
		{
			foreach($doks as $dokType)
			{
				if((!isset($this->_data["dokumente"][$dokType->dokument_kurzbz])) && ($dokType->pflicht == "t"))
				{
					$error["dokumente"][$key][$dokType->bezeichnung] = $dokType;
				}
			}
		}

		//check personal data
		$person = $this->_data["person"];

		if($person->vorname == "")
		{
			$error["person"]["vorname"] = true;
		}

		if($person->nachname == "")
		{
			$error["person"]["nachname"] = true;
		}

		if($person->gebdatum == null)
		{
			$error["person"]["geburtsdatum"] = true;
		}

		if(($person->gebort == null) || ($person->gebort== ""))
		{
			$error["person"]["geburtsort"] = true;
		}

		if(($person->geburtsnation == null) || ($person->geburtsnation== ""))
		{
			$error["person"]["geburtsnation"] = true;
		}

		if(($person->staatsbuergerschaft == null) || ($person->staatsbuergerschaft== ""))
		{
			$error["person"]["staatsbuergerschaft"] = true;
		}

		if((($person->svnr == null) || ($person->svnr== "")) && ($person->geburtsnation == "A"))
		{
			$error["person"]["svnr"] = true;
		}

		if(($person->geschlecht == null) || ($person->geschlecht == ""))
		{
			$error["person"]["geschlecht"] = true;
		}

		//check adress data
		if(isset($this->_data["adresse"]))
		{
			$adresse = $this->_data["adresse"];

			if(($adresse->strasse == null) || ($adresse->strasse == ""))
			{
				$error["adresse"]["strasse"] = true;
			}

			if(($adresse->plz == null) || ($adresse->plz == ""))
			{
				$error["adresse"]["plz"] = true;
			}

			if(($adresse->ort == null) || ($adresse->ort == ""))
			{
				$error["adresse"]["ort"] = true;
			}
		}
		else
		{
			$error["adresse"]['strasse']=true;
			$error["adresse"]['plz']=true;
			$error["adresse"]['ort']=true;
		}

		//check contact data
		$kontakt = $this->_data["kontakt"];

		if((!isset($kontakt["telefon"])) || ($kontakt["telefon"]->kontakt == ""))
		{
			$error["kontakt"]["telefon"] = true;
		}

		if((!isset($kontakt["email"])) || ($kontakt["email"]->kontakt == ""))
		{
			$error["kontakt"]["email"] = true;
		}

		if(!isset($this->_data["dokumente"][$this->config->config["dokumentTypen"]["lebenslauf"]]))
		{
			$error["doks"]["lebenslauf"] = true;
		}

		if(!isset($this->_data["dokumente"][$this->config->config["dokumentTypen"]["reisepass"]]))
		{
			$error["doks"]["reisepass"] = true;
		}

		return $error;
	}


}
