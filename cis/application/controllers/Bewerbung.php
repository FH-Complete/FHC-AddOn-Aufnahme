<?php
/**
 * ./cis/application/controllers/Bewerbung.php
 *
 * @package default
 */

class Bewerbung extends MY_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('person_model', 'PersonModel');
		$this->load->model('kontakt_model', 'KontaktModel');
		$this->load->model('nation_model');
		$this->load->model('bundesland_model');
		$this->load->model('adresse_model', "AdresseModel");
		$this->load->model('studiengang_model', "StudiengangModel");
		$this->load->model('prestudent_model', "PrestudentModel");
		$this->load->model('prestudentStatus_model', "PrestudentStatusModel");
		$this->load->model('studiensemester_model', "StudiensemesterModel");
		$this->load->model('studienplan_model', "StudienplanModel");
		$this->load->model('dms_model', "DmsModel");
		$this->load->model('akte_model', "AkteModel");
		$this->load->model('gemeinde_model', "GemeindeModel");
		$this->load->model('Bewerbungstermine_model', 'BewerbungstermineModel');
		$this->load->model('dokument_model', "DokumentModel");
		$this->load->model('DokumentStudiengang_model', "DokumentStudiengangModel");
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->_data["sprache"] = $this->get_language();
		$this->_loadLanguage($this->_data["sprache"]);
		$this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
	}

	/**
	 *
	 */
	public function index()
	{
		$this->checkLogin();

		$this->_data['title'] = 'Personendaten';

		if($this->input->get("studiengang_kz") != null)
		{
			$this->_data["studiengang_kz"] = $this->input->get("studiengang_kz");
		}

		//$this->StudiensemesterModel->getNextStudiensemester("WS");
		$this->session->set_userdata("studiensemester_kurzbz", $this->_getNextStudiensemester("WS"));

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load kontakt data
		$this->_loadKontakt();

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();

		$this->_data["studiengaenge"] = array();
		foreach ($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

			if ((!empty($prestudent->prestudentStatus))
				&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
					|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber")) {
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;

				$prestudent->spezialisierung = $this->_getSpecialization($prestudent->prestudent_id);
				$this->_data["spezialisierung"][$prestudent->studiengang_kz] = $prestudent->spezialisierung;

				if($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null)
				{
					$this->_data["bewerbung_abgeschickt"] = true;
				}
				array_push($this->_data["studiengaenge"], $studiengang);
			}
		}

		if(count($this->_data["studiengaenge"]) > 1)
		{
			usort($this->_data["studiengaenge"], array($this, "cmpStg"));
		}

		//load adress data
		$this->_loadAdresse();
		
		$this->showMissingData();

		//load nationen
		$this->_loadNationen();

		//load bundeslaender
		$this->_loadBundeslaender();

		//load gemeinden
		$this->_getGemeinde();

		foreach ($this->_data["gemeinden"] as $gemeinde)
		{
			if ((isset($this->_data["adresse"])) && ($gemeinde->plz == $this->_data["adresse"]->plz) && ($gemeinde->name == $this->_data["adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["adresse"]->ort))
			{
				$this->_data["ort_dd"] = $gemeinde->gemeinde_id;
			}

			if ((isset($this->_data["zustell_adresse"])) && ($gemeinde->plz == $this->_data["zustell_adresse"]->plz) && ($gemeinde->name == $this->_data["zustell_adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["zustell_adresse"]->ort))
			{
				$this->_data["zustell_ort_dd"] = $gemeinde->gemeinde_id;
			}
		}

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);

		foreach($this->_data["dokumente"] as $akte)
		{
			if ($akte->dms_id != null)
			{
				$dms = $this->_loadDms($akte->dms_id);
				$akte->dokument = $dms;
			}
		}

		//form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
		$this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
		$this->form_validation->set_rules("gebdatum", "Geburtsdatum", "callback_check_date");
		$this->form_validation->set_rules("email", "E-Mail", "required|valid_email");

		$reisepass = $this->_loadDokument($this->config->item("dokumentTypen")["reisepass"]);
		$lebenslauf = $this->_loadDokument($this->config->item("dokumentTypen")["lebenslauf"]);
		$this->_data["personalDocuments"] = array($this->config->item("dokumentTypen")["reisepass"]=>$reisepass, $this->config->item("dokumentTypen")["lebenslauf"]=>$lebenslauf);

		if ($this->form_validation->run() == FALSE)
		{
			$this->_data["complete"] = $this->_checkDataCompleteness();
			$this->load->view('bewerbung', $this->_data);
		}
		else
		{
			$post = $this->input->post();
			$person = $this->_data["person"];
			$person->vorname = $post["vorname"];
			$person->nachname = $post["nachname"];
			//$person->bundesland_code = $post["bundesland"];

			if (isset($post["gebdatum"]))
			{
				$person->gebdatum = date('Y-m-d', strtotime($post["gebdatum"]));
			}
			$person->gebort = $post["geburtsort"] != '' ? $post["geburtsort"] : null;
			$person->geburtsnation = $post["nation"] != '' ? $post["nation"] : null;

			if ($post["anrede"] === "Herr")
			{
				$person->geschlecht = "m";
				$person->anrede = $post["anrede"];
			}
			elseif ($post["anrede"] === "Frau")
			{
				$person->geschlecht = "w";
				$person->anrede = $post["anrede"];
			}
			else
			{
				$person->geschlecht = "u";
			}

			$person->staatsbuergerschaft = $post["staatsbuergerschaft"] != '' ? $post["staatsbuergerschaft"] : null;

			// An die SVNR wird v1, v2, v3, etc hinzugefuegt wenn die SVNR bereits vorhanden ist
			// In der Anzeige wird dies herausgefiltert. Deshalb muss beim Speichern der Daten
			// wieder die SVNR mit v1 etc geschickt werden wenn diese nicht geaendert wurde
			if ($post["svnr_orig"]!='' && mb_substr($post["svnr_orig"], 0, 10)==$post["svnr"])
			{
				$person->svnr = $post["svnr_orig"];
			}
			else
			{
				if($post["svnr"] != '')
				{
					$person->svnr = $post["svnr"];
				}
			}

			$person->titelpre = $post["titelpre"] != '' ? $post["titelpre"] : null;
			$person->titelpost = $post["titelpost"] != '' ? $post["titelpost"] : null;

			$this->_savePerson($person);

			$adresse = new stdClass();
			$zustell_adresse = new stdClass();

			if ($post["adresse_nation"] === "A")
			{
				if (($post["strasse"] != "") && ($post["plz"] != "") && ($post["ort_dd"] != ""))
				{
					if (isset($this->_data["adresse"]))
					{
						$adresse = $this->_data["adresse"];
					}
					else
					{
						$adresse->person_id = $this->_data["person"]->person_id;
						$adresse->heimatadresse = true;
					}

					if (($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != ""))))
					{
						$adresse->zustelladresse = false;
					}
					else
					{
						$adresse->zustelladresse = true;
					}

					$adresse->strasse = $post["strasse"];
					$adresse->nation = $post["adresse_nation"];
					$adresse->plz = $post["plz"];

					foreach($this->_data["gemeinden"] as $gemeinde)
					{
						if ($gemeinde->gemeinde_id === $post["ort_dd"])
						{
							$adresse->gemeinde = $gemeinde->name;
							$adresse->ort = $gemeinde->ortschaftsname;
							$person->bundesland_code = $gemeinde->bulacode;
						}
					}

					$this->_savePerson($person);
					$this->_saveAdresse($adresse);
				}
			}
			else
			{
				if (($post["strasse"] != "") && ($post["plz"] != "") && ($post["ort"] != ""))
				{
					if (isset($this->_data["adresse"]))
					{
						$adresse = $this->_data["adresse"];
					}
					else
					{
						$adresse->person_id = $this->_data["person"]->person_id;
						$adresse->heimatadresse = true;
					}

					if (($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != ""))))
					{
						$adresse->zustelladresse = false;
					}
					else
					{
						$adresse->zustelladresse = true;
					}

					$adresse->strasse = $post["strasse"];
					$adresse->plz = $post["plz"];
					$adresse->ort = $post["ort"];
					$adresse->nation = $post["adresse_nation"];

					$this->_saveAdresse($adresse);
				}
			}

			if ($post["zustelladresse_nation"] === "A")
			{
				if (($post["zustell_strasse"] != "") && (($post["zustell_plz"] != "") && ($post["zustell_ort_dd"] != "")))
				{
					if (isset($this->_data["zustell_adresse"]))
					{
						$zustell_adresse = $this->_data["zustell_adresse"];
					}
					else
					{
						$zustell_adresse->person_id = $this->_data["person"]->person_id;
						$zustell_adresse->heimatadresse = false;
						$zustell_adresse->zustelladresse = true;
					}

					$zustell_adresse->strasse = $post["zustell_strasse"];
					$zustell_adresse->nation = $post["zustelladresse_nation"];
					$zustell_adresse->plz = $post["zustell_plz"];

					foreach($this->_data["gemeinden"] as $gemeinde)
					{
						if ($gemeinde->gemeinde_id === $post["zustell_ort_dd"])
						{
							$zustell_adresse->gemeinde = $gemeinde->name;
							$zustell_adresse->ort = $gemeinde->ortschaftsname;
						}
					}

					foreach($this->_data["bundesland"] as $bundesland)
					{
						if ($bundesland->bundesland_code === $gemeinde->bulacode)
						{
							$person->bundesland_code = $bundesland->bundesland_code;
						}
					}

					$this->_saveAdresse($zustell_adresse);
				}
			}
			else
			{
				if (($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != ""))))
				{
					if (isset($this->_data["zustell_adresse"]))
					{
						$zustell_adresse = $this->_data["zustell_adresse"];
					}
					else
					{
						$zustell_adresse->person_id = $this->_data["person"]->person_id;
						$zustell_adresse->heimatadresse = false;
						$zustell_adresse->zustelladresse = true;
					}

					$zustell_adresse->strasse = $post["zustell_strasse"];
					$zustell_adresse->plz = $post["zustell_plz"];
					$zustell_adresse->ort = $post["zustell_ort"];
					$zustell_adresse->nation = $post["zustelladresse_nation"];

					$this->_saveAdresse($zustell_adresse);
				}
			}

			if (($post["email"] != ""))
			{
				if(!(isset($this->_data["kontakt"]["email"])))
				{
					$kontakt = new stdClass();
					$kontakt->person_id = $this->_data["person"]->person_id;
					$kontakt->kontakttyp = "email";
					$kontakt->kontakt = $post["email"];
					$kontakt->zustellung = true;
				}
				else
				{
					$kontakt = $this->_data["kontakt"]["email"];
					$kontakt->kontakt = $post["email"];
				}
				$this->_saveKontakt($kontakt);
			}

			if ((isset($post["telefon"])) && ($post["telefon"] != ""))
			{
				if(!(isset($this->_data["kontakt"]["telefon"])))
				{
					$kontakt = new stdClass();
					$kontakt->person_id = $this->_data["person"]->person_id;
					$kontakt->kontakttyp = "telefon";
					$kontakt->kontakt = $post["telefon"];
				}
				else
				{
					$kontakt = $this->_data["kontakt"]["telefon"];
					$kontakt->kontakt = $post["telefon"];
				}
				$this->_saveKontakt($kontakt);
			}

			if ((isset($post["fax"])) && ($post["fax"] != ""))
			{
				if(!(isset($this->_data["kontakt"]["fax"])))
				{
					$kontakt = new stdClass();
					$kontakt->person_id = $this->_data["person"]->person_id;
					$kontakt->kontakttyp = "fax";
					$kontakt->kontakt = $post["fax"];
				}
				else
				{
					$kontakt = $this->_data["kontakt"]["fax"];
					$kontakt->kontakt = $post["fax"];
				}
				$this->_saveKontakt($kontakt);
			}

			if((isset($post["spezialisierung"])) && (is_array($post["spezialisierung"])))
			{
				foreach ($this->_data["prestudent"] as $prestudent)
				{
					if(($prestudent->studiengang_kz === $this->input->get("studiengang_kz")) &&(empty($prestudent->spezialisierung)))
					{
						if ((!empty($prestudent->prestudentStatus))
							&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
								|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber"))
						{
							$text = "";
							foreach($post["spezialisierung"] as $spez)
							{
								$text .= $spez.";";
							}
							$text = substr($text, 0, -1);
							if (substr_count($text, ';') !== strlen($text))
							{
								$this->_saveSpecialization($prestudent->prestudent_id, $text);
								$prestudent->spezialisierung = $this->_getSpecialization($prestudent->prestudent_id);
								$this->_data["spezialisierung"][$prestudent->studiengang_kz] = $prestudent->spezialisierung;
							}
						}
					}
				}
			}

			$this->_loadPerson();
			$this->_loadKontakt();
			$this->_loadAdresse();

			foreach($this->_data["gemeinden"] as $gemeinde)
			{
				if ((isset($this->_data["adresse"])) && ($gemeinde->plz == $this->_data["adresse"]->plz) && ($gemeinde->name == $this->_data["adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["adresse"]->ort))
				{
					$this->_data["ort_dd"] = $gemeinde->gemeinde_id;
				}

				if ((isset($this->_data["zustell_adresse"])) && ($gemeinde->plz == $this->_data["zustell_adresse"]->plz) && ($gemeinde->name == $this->_data["zustell_adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["zustell_adresse"]->ort))
				{
					$this->_data["zustell_ort_dd"] = $gemeinde->gemeinde_id;
				}
			}

			if((!isset($this->_data["error"])) && (isset($this->input->get()["studiengang_kz"])) && (isset($this->input->get()["studienplan_id"])))
			{
				redirect("/Requirements?studiengang_kz=".$this->input->get()["studiengang_kz"]."&studienplan_id=".$this->input->get()["studienplan_id"]);
				$this->_data["complete"] = $this->_checkDataCompleteness();
				$this->load->view('bewerbung', $this->_data);
			}
			else
			{
				$this->_data["complete"] = $this->_checkDataCompleteness();
				$this->load->view('bewerbung', $this->_data);
			}
		}
	}

	/**
	 *
	 * @return unknown
	 */
	public function check_date() {
		$date = explode(".", $this->input->post("gebdatum"));
		if (!checkdate($date[1], $date[0], $date[2])) {
			//$this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
			$this->form_validation->set_message("check_date", "Bitte geben Sie ein gültiges Datum an.");
			return false;
		}
		return true;
	}

	/**
	 *
	 * @param unknown $studiengang_kz
	 * @param unknown $studienplan_id
	 */
	public function studiengang($studiengang_kz, $studienplan_id, $studiensemester_kurzbz)
	{
		$this->checkLogin();

		$this->session->set_userdata("studiengang_kz", $studiengang_kz);

		$this->session->set_userdata("studiensemester_kurzbz", $studiensemester_kurzbz);

		$this->_data["studiengang_kz"] = $studiengang_kz;

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();

		//load Studienplan
		$this->_data["studienplan"] = $this->_loadStudienplan($studienplan_id);

		$fristen = $this->_getBewerbungstermineStudienplan($studienplan_id);

		$bewerbungMoeglich = false;
		if (!empty($fristen))
		{
			foreach($fristen as $frist)
			{
				if ((date("Y-m-d", strtotime($frist->beginn)) < date("Y-m-d")) && (date("Y-m-d", strtotime($frist->ende)) > date("Y-m-d")))
				{
					$bewerbungMoeglich = true;
				}
			}
		}

		if ($bewerbungMoeglich)
		{
			$exists = false;
			$prestudentStatus = null;
			foreach($this->_data["prestudent"] as $prestudent)
			{
				$prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
				if (($prestudent->studiengang_kz == $studiengang_kz) && (is_object($prestudentStatus)) &&
					($prestudentStatus->studienplan_id == $studienplan_id) &&
					($prestudentStatus->studiensemester_kurzbz == $this->session->userdata()["studiensemester_kurzbz"]))
				{
					$exists = true;
				}
				$prestudentStatus = null;
			}

			if ((!$exists) && ($this->PrestudentModel->result->error == 0))
			{
				$prestudent = $this->_savePrestudent($studiengang_kz);
				$this->_data["prestudent"] = $this->_loadPrestudent();
				$this->_savePrestudentStatus($prestudent, "Interessent");
			}
			else
			{
				//TODO handle error
				if ($this->PrestudentModel->result->error != 0)
				{
					var_dump($this->PrestudentModel->result);
				}
			}
		}
		else
		{
			redirect("/Studiengaenge");
		}

		//load adress data
		$this->_loadAdresse();

		//load kontakt data
		$this->_loadKontakt();
		
		$this->showMissingData();

		//load nationen
		$this->_loadNationen();

		//load bundeslaender
		$this->_loadBundeslaender();

		//load gemeinden
		$this->_data["gemeinden"] = $this->_loadGemeinde();
		foreach($this->_data["gemeinden"] as $gemeinde)
		{
			if ((isset($this->_data["adresse"])) && ($gemeinde->plz == $this->_data["adresse"]->plz) && ($gemeinde->name == $this->_data["adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["adresse"]->ort))
			{
				$this->_data["ort_dd"] = $gemeinde->gemeinde_id;
			}

			if ((isset($this->_data["zustell_adresse"])) && ($gemeinde->plz == $this->_data["zustell_adresse"]->plz) && ($gemeinde->name == $this->_data["zustell_adresse"]->gemeinde) && ($gemeinde->ortschaftsname == $this->_data["zustell_adresse"]->ort))
			{
				$this->_data["zustell_ort_dd"] = $gemeinde->gemeinde_id;
			}
		}

		//load studiengang
		$this->_data["studiengang"] = $this->_loadStudiengang($studiengang_kz);

		$this->_data["studiengaenge"] = array();
		foreach($this->_data["prestudent"] as $prestudent)
		{
			//load studiengaenge der prestudenten
			$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

			if ((!empty($prestudent->prestudentStatus)) && ($prestudent->prestudentStatus->status_kurzbz === "Interessent"))
			{
				$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
				$studiengang->studienplan = $studienplan;
				$prestudent->spezialisierung = $this->_getSpecialization($prestudent->prestudent_id);
				$this->_data["spezialisierung"][$prestudent->studiengang_kz] = $prestudent->spezialisierung;
				array_push($this->_data["studiengaenge"], $studiengang);
			}

			if((!empty($prestudent->prestudentStatus)) && ($prestudent->prestudentStatus->bewerbung_abgeschicktamum != null))
			{
				$this->_data["bewerbung_abgeschickt"] = true;
			}
		}

		if(count($this->_data["studiengaenge"]) > 1)
		{
			usort($this->_data["studiengaenge"], array($this, "cmpStg"));
		}

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);

		foreach($this->_data["dokumente"] as $akte)
		{
			if ($akte->dms_id != null)
			{
				$dms = $this->_loadDms($akte->dms_id);
				$akte->dokument = $dms;
			}
		}

		$reisepass = $this->_loadDokument($this->config->item("dokumentTypen")["reisepass"]);
		$lebenslauf = $this->_loadDokument($this->config->item("dokumentTypen")["lebenslauf"]);
		$this->_data["personalDocuments"] = array($this->config->item("dokumentTypen")["reisepass"]=>$reisepass, $this->config->item("dokumentTypen")["lebenslauf"]=>$lebenslauf);

		$this->_data["complete"] = $this->_checkDataCompleteness();
		$this->load->view('bewerbung', $this->_data);
	}

	/**
	 *
	 * @param unknown $studiengang_kz
	 */
	public function storno($studiengang_kz)
	{
		$this->checkLogin();

		$this->session->set_userdata("studiengang_kz", $studiengang_kz);

		//$this->StudiensemesterModel->getNextStudiensemester("WS");
		$this->session->set_userdata("studiensemester_kurzbz", $this->_getNextStudiensemester("WS"));

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load preinteressent data
		$this->_data["prestudent"] = $this->_loadPrestudent();

		$prestudentStatus = null;
		foreach($this->_data["prestudent"] as $prestudent)
		{
			if ($prestudent->studiengang_kz === $studiengang_kz)
			{
				$prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

				if ($prestudentStatus !== null)
				{
					$this->_deletePrestudentStatus(get_object_vars($prestudentStatus));
				}

				$this->_deletePrestudent(get_object_vars($prestudent));

				redirect("/Bewerbung");
			}
		}
	}

	public function uploadFiles($typ)
	{
		$files = $_FILES;

		if (count($files) > 0)
		{
			//load person data
			$this->_data["person"] = $this->_loadPerson();

			//load dokumente
			$this->_loadDokumente($this->session->userdata()["person_id"]);

			foreach($this->_data["dokumente"] as $akte)
			{
				if ($akte->dms_id != null)
				{
					$dms = $this->_loadDms($akte->dms_id);
					$akte->dokument = $dms;
				}
			}

			foreach($files as $key => $file)
			{
				if (is_uploaded_file($file["tmp_name"][0]))
				{
					$akte = new stdClass();
					$obj = new stdClass();
					$obj->new = true;
					$obj->version = 0;
					$obj->mimetype = $file["type"][0];
					$obj->name = $file["name"][0];
					$obj->oe_kurzbz = null;

					switch($key)
					{
						case "reisepass":
							$obj->dokument_kurzbz = $this->config->item('dokumentTypen')["reisepass"];
							break;
						case "lebenslauf":
							$obj->dokument_kurzbz = $this->config->item('dokumentTypen')["lebenslauf"];
							break;
						default:
							$obj->dokument_kurzbz = $this->config->item('dokumentTypen')["sonstiges"];
					}

					if ($typ)
						$obj->dokument_kurzbz = $this->config->item('dokumentTypen')[$typ];

					foreach($this->_data["dokumente"] as $akte_temp)
					{
						if (($akte_temp->dokument_kurzbz == $obj->dokument_kurzbz) && ($obj->dokument_kurzbz != $this->config->item('dokumentTypen')["sonstiges"]))
						{
							$akte = $akte_temp;
							$akte->updateamum = date("Y-m-d H:i:s");
							$akte->updatevon = "online";

							if ($akte->dms_id != null && !is_null($akte->dokument))
							{
								$obj = $akte->dokument;
								$obj->new = true;
								$obj->version = ($obj->version + 1);
								//$obj->version = ($akte->dokument->version+1);
								$obj->mimetype = $file["type"][0];
								$obj->name = $file["name"][0];
							}
						}
					}

					$obj->kategorie_kurzbz = "Akte";

					$type = pathinfo($file["name"][0], PATHINFO_EXTENSION);
					$data = file_get_contents($file["tmp_name"][0]);
					$obj->file_content = base64_encode($data);

					$result = new stdClass();
					$this->_saveDms($obj);
					if ($this->DmsModel->result->error == 0)
					{
						if ($obj->version >= 0)
						{
							$akte->dms_id = $this->DmsModel->result->retval->dms_id;
							$result->dms_id = $akte->dms_id;
							$akte->person_id = $this->_data["person"]->person_id;
							$akte->mimetype = $file["type"][0];

							$akte->bezeichnung = mb_substr($obj->name, 0, 32);
							$akte->dokument_kurzbz = $obj->dokument_kurzbz;
							$akte->titel = $key;
							$akte->insertvon = 'online';
							$akte->nachgereicht = false;

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

							if ($this->_saveAkte($akte))
							{
								$result->success = true;
								$result->akte_id = $this->AkteModel->result->retval;
								$result->bezeichnung = $obj->name;
								$result->mimetype = $akte->mimetype;
							}
							else
							{
								$result->success = false;
							}
						}
						else
						{
							$akte->mimetype = $file["type"][0];
							$akte->bezeichnung = mb_substr($obj->name, 0, 32);
							$akte->dokument_kurzbz = $obj->dokument_kurzbz;
							$akte->titel = $key;

							unset($akte->uid);
							unset($akte->inhalt_vorhanden);
							$akte->dokument = null;
							unset($akte->dokument);
							unset($akte->nachgereicht_am);

							if ($this->_saveAkte($akte))
							{
								$result->success = true;
							}
							else
							{
								$result->success = false;
							}
						}
						echo json_encode($result);
					}
					else
					{
						$result->success = false;
						echo json_encode($result);
						$this->_setError(true, $this->DmsModel->getErrorMessage());
					}

					if (unlink($file["tmp_name"][0]))
					{
						//removing tmp file successful
					}
				}
			}
		}
	}

	/**
	 *
	 * @param type $notiz_id
	 */
	public function deleteSpezialisierung($notiz_id, $studiengang_kz)
	{
		$this->_data["prestudent"] = $this->_loadPrestudent();

//		$this->_data["studiengaenge"] = array();
		foreach ($this->_data["prestudent"] as $prestudent)
		{
			if($prestudent->studiengang_kz === $studiengang_kz)
			{
				$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);

				if ((!empty($prestudent->prestudentStatus))
					&& ($prestudent->prestudentStatus->status_kurzbz === "Interessent"
						|| $prestudent->prestudentStatus->status_kurzbz === "Bewerber"))
				{
					$prestudent->spezialisierung = $this->_getSpecialization($prestudent->prestudent_id);

					if((!empty($prestudent->spezialisierung)) && ($prestudent->spezialisierung->notiz_id === $notiz_id))
					{
						$this->_removeSpecialization($notiz_id);
						redirect("/Bewerbung?studiengang_kz=".$studiengang_kz."&tudienplan_id=".$prestudent->prestudentStatus->studienplan_id);
					}
				}
			}
		}
	}

	private function _loadPerson()
	{
		$this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"]));
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

	private function _loadPrestudent()
	{
		$this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"]));
		if ($this->PrestudentModel->isResultValid() === true)
		{
			if(count($this->PrestudentModel->result->retval) > 1)
			{

			}
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
		$this->PrestudentStatusModel->getLastStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1));
		if($this->PrestudentStatusModel->isResultValid() === true)
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

	private function _loadKontakt()
	{
		$this->KontaktModel->getKontakt($this->session->userdata()["person_id"]);
		if ($this->KontaktModel->isResultValid() === true)
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
		if ($this->AdresseModel->isResultValid() === true)
		{
			foreach($this->AdresseModel->result->retval as $adresse)
			{
				if ($adresse->heimatadresse == true)
				{
					$this->_data["adresse"] = $adresse;
				}
				else if (($adresse->heimatadresse == false) && ($adresse->zustelladresse == true))
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

	private function _loadNationen()
	{
		$this->nation_model->getNationen();
		if ($this->nation_model->isResultValid() === true)
		{
			$nation_code = "";
			$kurztext = "";
			foreach($this->nation_model->result->retval as $n)
			{
				if($n->nation_code == "A")
				{
					$nation_code = $n->nation_code;
					$kurztext = $n->kurztext;
				}
				else
				{
					$this->_data["nationen"][$n->nation_code] = $n->kurztext;
				}
			}
			
			$this->_data["nationen"] = array_merge(array("null"=>"", $nation_code => $kurztext), $this->_data["nationen"]);
		}
		else
		{
			$this->_setError(true, $this->nation_model->getErrorMessage());
		}
	}

	private function _loadBundeslaender()
	{
		$this->bundesland_model->getBundeslaender();
		if ($this->bundesland_model->isResultValid() === true)
		{
			$this->_data["bundesland"] = $this->bundesland_model->result->retval;
			foreach($this->bundesland_model->result->retval as $b)
			{
				$this->_data["bundeslaender"][$b->bundesland_code] = $b->bezeichnung;
			}
		}
		else
		{
			$this->_setError(true, $this->bundesland_model->getErrorMessage());
		}
	}

	private function _loadStudiengang($stgkz = null)
	{
		if (is_null($stgkz))
		{
			$stgkz = $this->_data["prestudent"][0]->studiengang_kz;
		}

		$this->StudiengangModel->getStudiengang($stgkz);
		if ($this->StudiengangModel->isResultValid() === true)
		{
			if (count($this->StudiengangModel->result->retval) == 1)
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
		if ($this->StudienplanModel->isResultValid() === true)
		{
			if (count($this->StudienplanModel->result->retval) == 1)
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

	private function _savePerson($person)
	{
		$this->PersonModel->savePerson($person);
		if ($this->PersonModel->isResultValid() === true)
		{
			//TODO Daten erfolgreich gespeichert
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}

	private function _savePrestudent($studiengang_kz)
	{
		$prestudent = new stdClass();
		$prestudent->person_id = $this->session->userdata()["person_id"];
		$prestudent->studiengang_kz = $studiengang_kz;
		$prestudent->aufmerksamdurch_kurzbz = 'k.A.';
		$this->PrestudentModel->savePrestudent($prestudent);
		if ($this->PrestudentModel->isResultValid() === true)
		{
			//TODO Daten erfolgreich gespeichert
			$prestudent->prestudent_id = $this->PrestudentModel->result->retval;
			return $prestudent;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _savePrestudentStatus($prestudent, $status_kurzbz)
	{
		$prestudentStatus = new stdClass();
		$prestudentStatus->new = true;
		$prestudentStatus->prestudent_id = $prestudent->prestudent_id;
		$prestudentStatus->status_kurzbz = $status_kurzbz;
		$prestudentStatus->rt_stufe = 1;

		//if(($this->StudiensemesterModel->result->error == 0) && (count($this->StudiensemesterModel->result->retval) > 0))
		//{
			$prestudentStatus->studiensemester_kurzbz = $this->session->userdata()["studiensemester_kurzbz"];
			//nicht notwendig da defaultwert 1
			//$prestudentStatus->ausbildungssemester = "1";
			$prestudentStatus->orgform_kurzbz = $this->_data["studienplan"]->orgform_kurzbz;
			$prestudentStatus->studienplan_id = $this->_data["studienplan"]->studienplan_id;
			$prestudentStatus->datum = date("Y-m-d");

			$this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
			if ($this->PrestudentStatusModel->isResultValid() === true)
			{
				//TODO Daten erfolgreich gespeichert
				foreach($this->_data["prestudent"] as $key=>$value)
				{
					if ($value->prestudent_id == $prestudent->prestudent_id)
					{
						$this->_data["prestudent"][$key]->prestudentstatus = $prestudentStatus;
					}
				}
			}
			else
			{
				$this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
			}
		/*}
		else
		{
			//studiensemester not found
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
		}*/
	}

	private function _deletePrestudentStatus($prestudentStatus)
	{
		$this->PrestudentStatusModel->deletePrestudentStatus($prestudentStatus);
		if ($this->PrestudentStatusModel->isResultValid() === true)
		{
			// TODO Ok!
		}
		else
		{
			$this->_setError(true, $this->PrestudentStatusModel->getErrorMessage());
		}
	}

	private function _deletePrestudent($prestudent)
	{
		$this->PrestudentModel->deletePrestudent($prestudent);
		if ($this->PrestudentModel->isResultValid() === true)
		{
			// TODO Ok!
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _saveKontakt($kontakt)
	{
		$this->KontaktModel->saveKontakt($kontakt);
		if ($this->KontaktModel->isResultValid() === true)
		{
			//TODO Daten erfolgreich gespeichert
		}
		else
		{
			$this->_setError(true, $this->KontaktModel->getErrorMessage());
		}
	}

	private function _saveAdresse($adresse)
	{
		$this->AdresseModel->saveAdresse($adresse);
		if ($this->AdresseModel->isResultValid() === true)
		{
			//TODO Daten erfolgreich gespeichert
		}
		else
		{
			$this->_setError(true, $this->AdresseModel->getErrorMessage());
		}
	}

	private function _loadDokumente($person_id, $dokumenttyp_kurzbz=null)
	{
		$this->_data["dokumente"] = array();
		$this->AkteModel->getAktenAccepted($person_id, $dokumenttyp_kurzbz);

		if ($this->AkteModel->isResultValid() === true)
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
		if ($this->DmsModel->isResultValid() === true)
		{
			if (count($this->DmsModel->result->retval) == 1)
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

	private function _loadGemeinde()
	{
		$this->GemeindeModel->getGemeinde();
		if ($this->GemeindeModel->isResultValid() === true)
		{
			return $this->GemeindeModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->GemeindeModel->getErrorMessage());
		}
	}

	private function _getNextStudiensemester($art)
	{
		$this->StudiensemesterModel->getNextStudiensemester($art);
		if ($this->StudiensemesterModel->isResultValid() === true)
		{
			return $this->StudiensemesterModel->result->retval[0]->studiensemester_kurzbz;
		}
		else
		{
			$this->_setError(true, $this->StudiensemesterModel->getErrorMessage());
		}
	}

	private function _saveDms($dms)
	{
		$this->DmsModel->saveDms($dms);
		if ($this->DmsModel->isResultValid() === true)
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
		if ($this->AkteModel->isResultValid() === true)
		{
			//TODO saved successfully
			return true;
		}
		else
		{
			$this->_setError(true, $this->AkteModel->getErrorMessage());
		}
	}

	private function _getGemeinde()
	{
		if (!isset($this->_data["gemeinden"]))
		{
			$this->_data["gemeinden"] = $this->_loadGemeinde();
		}
		if (isset($this->_data["adresse"]))
		{
			$this->_data["gemeinde"] = $this->_data["adresse"]->plz." ".$this->_data["adresse"]->gemeinde.", ".$this->_data["adresse"]->ort;
		}

		if (isset($this->_data["zustell_adresse"]))
		{
			$this->_data["zustell_gemeinde"] = $this->_data["zustell_adresse"]->plz." ".$this->_data["zustell_adresse"]->gemeinde.", ".$this->_data["zustell_adresse"]->ort;
		}
	}


	private function _getBewerbungstermineStudienplan($studienplan_id)
	{
		$this->BewerbungstermineModel->getByStudienplan($studienplan_id);
		if($this->BewerbungstermineModel->isResultValid() === true)
		{
			return $this->BewerbungstermineModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->BewerbungstermineModel->getErrorMessage());
		}
	}

	/**
	 *
	 * @return unknown
	 */
	public function deleteDocument()
	{
		$result = new stdClass();
		if((isset($this->input->post()["dms_id"])))
		{
			$dms_id = $this->input->post()["dms_id"];
			$this->_loadDokumente($this->session->userdata()["person_id"]);

			foreach($this->_data["dokumente"] as $dok)
			{
				if(($dok->dms_id === $dms_id) && ($dok->accepted ==false))
				{
					$result = $this->_deleteDms($dms_id);
					$result->dokument_kurzbz = $dok->dokument_kurzbz;
				}
			}
		}
		else
		{
			//TODO parameter missing
			$result->error = true;
			$result->msg = "dms_id is missing";
		}

		echo json_encode($result);
	}

	private function _deleteDms($dms_id)
	{
		$this->DmsModel->deleteDms($this->session->userdata("person_id"), $dms_id);
		if ($this->DmsModel->isResultValid() === true)
		{
			return $this->DmsModel->result;
		}
		else
		{
			$this->_setError(true, $this->DmsModel->getErrorMessage());
		}
	}

	private function _loadGemeindeByPlz($plz)
	{
		$this->GemeindeModel->getGemeindeByPlz($plz);
		if ($this->GemeindeModel->isResultValid() === true)
		{
			return $this->GemeindeModel->result;
		}
		else
		{
			$this->_setError(true, $this->GemeindeModel->getErrorMessage());
		}
	}
	
	private function showMissingData()
	{
		if(($this->_data["person"]->anrede != null)
			||($this->_data["person"]->titelpre != null)
			||($this->_data["person"]->titelpost != null)
			||($this->_data["person"]->gebort != null)
			||($this->_data["person"]->staatsbuergerschaft != null)
			||($this->_data["person"]->geburtsnation != null)
			||($this->_data["person"]->svnr != null)
			||(!isset($this->_data["kontakt"]['telefon']) || $this->_data["kontakt"]['telefon']->kontakt != null)
			||(!isset($this->_data["adresse"]) || $this->_data["adresse"]->plz != null || $this->_data["adresse"]->strasse != null || $this->_data["adresse"]->ort != null || $this->_data["adresse"]->nation != null)
			||(!isset($this->_data["zustell_adresse"]) || $this->_data["zustell_adresse"]->plz != null || $this->_data["zustell_adresse"]->strasse != null || $this->_data["zustell_adresse"]->ort != null || $this->_data["zustell_adresse"]->nation != null)
				)
		{
			$this->_data["incomplete"] = true;
		}
		else
		{
			$this->_data["incomplete"] = false;
		}
	}

	public function ort($plz)
	{
		echo json_encode($this->_loadGemeindeByPlz($plz));
	}

	public function checkDataCompleteness()
	{
		//$this->StudiensemesterModel->getNextStudiensemester("WS");
		$this->session->set_userdata("studiensemester_kurzbz", $this->_getNextStudiensemester("WS"));
		$reisepass = $this->_loadDokument($this->config->item("dokumentTypen")["reisepass"]);
		$lebenslauf = $this->_loadDokument($this->config->item("dokumentTypen")["lebenslauf"]);
		$this->_data["personalDocuments"] = array($this->config->item("dokumentTypen")["reisepass"]=>$reisepass, $this->config->item("dokumentTypen")["lebenslauf"]=>$lebenslauf);

		//load person data
		$this->_data["person"] = $this->_loadPerson();

		//load kontakt data
		$this->_loadKontakt();

		//load adress data
		$this->_loadAdresse();

		//load dokumente
		$this->_loadDokumente($this->session->userdata()["person_id"]);
		
		$studiengang_kz = $this->input->get("studiengang_kz");
		
		$this->_data["dokumenteStudiengang"][$studiengang_kz] = $this->_loadDokumentByStudiengang($studiengang_kz);

		foreach($this->_data["dokumente"] as $akte)
		{
			if ($akte->dms_id != null)
			{
				$dms = $this->_loadDms($akte->dms_id);
				$akte->dokument = $dms;
			}
		}

		echo json_encode($this->_checkDataCompleteness());
	}

	private function _checkDataCompleteness()
	{
		$complete = array("person"=>true, "adresse"=>true, "kontakt"=>true, "zustelladresse"=>true, "dokumente"=>true);

		//check personal data
		$person = $this->_data["person"];

		if($person->vorname == "")
		{
			$complete["person"] = false;
		}

		if($person->nachname == "")
		{
			$complete["person"] = false;
		}

		if($person->gebdatum == null)
		{
			$complete["person"] = false;
		}

		if(($person->gebort == null) || ($person->gebort== ""))
		{
			$complete["person"] = false;
		}

		if(($person->geburtsnation == null) || ($person->geburtsnation== ""))
		{
			$complete["person"] = false;
		}

		if(($person->staatsbuergerschaft == null) || ($person->staatsbuergerschaft== ""))
		{
			$complete["person"] = false;
		}

		if((($person->svnr == null) || ($person->svnr== "")) && ($person->geburtsnation == "A"))
		{
			$complete["person"] = false;
		}

		//check adress data
		if(isset($this->_data["adresse"]))
		{
			$adresse = $this->_data["adresse"];

			if(($adresse->strasse == null) || ($adresse->strasse == ""))
			{
				$complete["adresse"] = false;
			}

			if(($adresse->plz == null) || ($adresse->plz == ""))
			{
				$complete["adresse"] = false;
			}

			if(($adresse->ort == null) || ($adresse->ort == ""))
			{
				$complete["adresse"] = false;
			}
		}
		else
		{
			$complete['adresse'] = false;
		}

		//check adress data
		if(isset($this->_data["zustell_adresse"]))
		{
			$adresse = $this->_data["zustell_adresse"];

			if(($adresse->strasse == null) || ($adresse->strasse == ""))
			{
				$complete["zustelladresse"] = false;
			}

			if(($adresse->plz == null) || ($adresse->plz == ""))
			{
				$complete["zustelladresse"] = false;
			}

			if(($adresse->ort == null) || ($adresse->ort == ""))
			{
				$complete["zustelladresse"] = false;
			}
		}


		//check contact data
		$kontakt = $this->_data["kontakt"];

		if((!isset($kontakt["telefon"])) || ($kontakt["telefon"]->kontakt == ""))
		{
			$complete["kontakt"] = false;
		}

		if((!isset($kontakt["email"])) || ($kontakt["email"]->kontakt == ""))
		{
			$complete["kontakt"] = false;
		}

		//check documents

		if(!isset($this->_data["dokumente"][$this->config->config["dokumentTypen"]["lebenslauf"]]))
		{
			$complete["dokumente"] = false;
		}

		if(!isset($this->_data["dokumente"][$this->config->config["dokumentTypen"]["reisepass"]]))
		{
			$complete["dokumente"] = false;
		}
		
		if(isset($this->_data["dokumenteStudiengang"]))
		{
			foreach($this->_data["dokumenteStudiengang"] as $key=>$doks)
			{
				foreach($doks as $dokType)
				{
					if((!isset($this->_data["dokumente"][$dokType->dokument_kurzbz])) && ($dokType->pflicht == true))
					{
						$complete["dokumente"] = false;
					}
				}

				foreach($this->_data["personalDocuments"] as $dokType)
				{
					if((!isset($this->_data["dokumente"][$dokType->dokument_kurzbz])))
					{
						$complete["dokumente"] = false;
					}
				}
				
				$abschlusszeugnis = $this->_loadDokument($this->config->item("dokumentTypen")["abschlusszeugnis"]);
				$letztesZeugnis = $this->_loadDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"]);

				if((!isset($this->_data["dokumente"][$abschlusszeugnis->dokument_kurzbz])) || ((!$this->_data["dokumente"][$abschlusszeugnis->dokument_kurzbz]->nachgereicht) && ($this->_data["dokumente"][$abschlusszeugnis->dokument_kurzbz]->dms_id == null )))
				{
					$complete["dokumente"] = false;
				}
				elseif((!isset($this->_data["dokumente"][$letztesZeugnis->dokument_kurzbz])) && ($this->_data["dokumente"][$abschlusszeugnis->dokument_kurzbz]->nachgereicht))
				{
					$complete["dokumente"] = false;
				}
			}
		}

		return $complete;
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

	private function _getSpecialization($prestudent_id)
	{
		$this->PrestudentModel->getSpecialization($prestudent_id, "aufnahme/spezialisierung");
		if ($this->PrestudentModel->isResultValid() === true)
		{
			if(count($this->PrestudentModel->result->retval) === 1)
			{
				return $this->PrestudentModel->result->retval[0];
			}
			else
			{
				return array();
			}
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _removeSpecialization($notiz_id)
	{
		$this->PrestudentModel->removeSpecialization($notiz_id);
		if ($this->PrestudentModel->isResultValid() === true)
		{
			return $this->PrestudentModel->result;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _saveSpecialization($prestudent_id, $text)
	{
		$this->PrestudentModel->saveSpecialization($prestudent_id, "aufnahme/spezialisierung", $text);
		if ($this->PrestudentModel->isResultValid() === true)
		{
			return $this->PrestudentModel->result;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}

	private function _loadDokument($dokument_kurzbz)
	{
		$this->DokumentModel->getDokument($dokument_kurzbz);
		if($this->DokumentModel->isResultValid() === true)
		{
			return $this->DokumentModel->result->retval[0];
		}
		else
		{
			$this->_setError(true, $this->DokumentModel->getErrorMessage());
		}
	}
}
