<?php
/**
 * ./cis/application/controllers/Send.php
 *
 * @package default
 */


class Send extends UI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();

        $currentLanguage = $this->getCurrentLanguage();
        if (hasData($currentLanguage))
        {
            $this->setData('sprache', $currentLanguage);
            $this->lang->load(array('send'), $this->getData('sprache'));
        }

        $this->load->helper("form");

        // Loading the
        $this->load->model('system/Phrase_model', 'PhraseModel');

        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');
        $this->load->model('organisation/Studienplan_model', 'StudienplanModel');
        $this->load->model('organisation/Studiengangstyp_model', 'StudiengangstypModel');

        $this->load->model('person/Person_model', 'PersonModel');
        $this->load->model('person/Adresse_model', 'AdresseModel');
        $this->load->model('person/Kontakt_model', 'KontaktModel');

        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');
        $this->load->model('crm/Akte_model', 'AkteModel');
        $this->load->model('crm/Dokument_model', 'DokumentModel');
        $this->load->model('crm/Dokumentprestudent_model', 'DokumentPrestudentModel');
        $this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');

        $this->load->model('content/Dms_model', 'DmsModel');

        $this->load->model('system/Message_model', 'MessageModel');

        $this->PhraseModel->getPhrasen(
            'aufnahme',
            ucfirst($this->getData('sprache')),
            REST_Model::AUTH_NOT_REQUIRED
        );
    }

	/**
	 *
	 */
	public function index()
	{
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

		if($this->input->get("studiengang_kz") != null)
		{
			$this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
            //load studiengang
            $this->setData("studiengang", $this->StudiengangModel->getStudiengang($this->input->get()["studiengang_kz"]));
		}

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent',
                true
            ));
        }

        $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

        $this->setData('adresse', $this->AdresseModel->getAdresse());

        $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

        $this->setData(
            'prestudent',
            $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                null,
                true
            )
        );
		
		//$this->_data["studiengaenge"] = array();
		$prestudentStatus = array();
		foreach ($this->getData("prestudent") as $prestudent)
		{
			//load studiengaenge der prestudenten
            //if($prestudent->studiengang_kz == $this->getData("studiengang_kz"))
            {
                /*$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
                $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);*/
                $prestudentStatus[$prestudent->studiengang_kz] = $prestudent;

                if (($prestudent->status_kurzbz === "Interessent"
                        || $prestudent->status_kurzbz === "Bewerber")
                )
                {
                    /*$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
                    $studiengang->studienplan = $studienplan;
                    $studiengang->studienplaene = array($studienplan);*/

                    if ($prestudent->bewerbung_abgeschicktamum != null)
                    {
                        $this->setRawData("bewerbung_abgeschickt", true);
                    }
                    //array_push($this->_data["studiengaenge"], $studiengang);
                }
            }
		}

		$this->setRawData("prestudentStatus", $prestudentStatus);

		/*
		if(count($this->_data["studiengaenge"]) > 1)
		{
			usort($this->_data["studiengaenge"], array($this, "cmpStg"));
		}*/

		//load Dokumente from Studiengang
        $dokumenteStudiengang = array();

        foreach($this->getData('studiengaenge') as $stg)
        {
            $dokumenteStudiengang[$stg->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($stg->studiengang_kz, true, true)->retval;
        }

        $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

		//load dokumente
        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        //adding abschlusszeugnis if it is not present in dokumente
        if(!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
        {
            $akten = $this->AkteModel->getAktenAccepted();

            if (hasData($akten))
            {
                if (isset($akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
                {
                    $dok = $akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]];
                    $dokumente = $this->getData('dokumente');
                    $dokumente[$dok->dokument_kurzbz] = $dok;
                    $this->setRawData('dokumente', $dokumente);
                }
            }
        }

        $this->_getPersonalDocuments();

		$this->setRawData("completenessError", $this->_checkDataCompleteness());

		$this->load->view('send', $this->getAllData());
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @param unknown $studienplan_id
	 */
	public function send($studiengang_kz, $studienplan_id)
	{
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

        $this->setRawData("studiengang_kz", $studiengang_kz);
        //load studiengang
        $this->setData("studiengang", $this->StudiengangModel->getStudiengang($studiengang_kz));

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
            $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
                $this->getData('studiensemester')->studiensemester_kurzbz,
                '',
                'Interessent',
                true
            ));
        }

		//load studiengang
		$this->setData("studiengang", $this->StudiengangModel->getStudiengang($studiengang_kz, true));

		//$this->_data["studiengang"]->studiengangstyp = $this->_loadStudiengangstyp($this->_data["studiengang"]->typ);

        $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

        $this->setData('adresse', $this->AdresseModel->getAdresse());

        $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

        $this->setData(
            'prestudent',
            $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                null,
                true
            )
        );

		//$this->_data["studiengaenge"] = array();
		$prestudentStatus = array();
		foreach ($this->getData("prestudent") as $prestudent)
		{
            //if($prestudent->studiengang_kz == $studiengang_kz)
            {
                //load studiengaenge der prestudenten
                //$studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
                //$prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                $prestudentStatus[$prestudent->studiengang_kz] = $prestudent;

                if (($prestudent->status_kurzbz === "Interessent"
                        || $prestudent->status_kurzbz === "Bewerber")
                )
                {
                    //$studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
                    //$studiengang->studienplan = $studienplan;
                    //$studiengang->studienplaene = array($studienplan);

                    if ($prestudent->bewerbung_abgeschicktamum != null)
                    {
                        $this->setData("bewerbung_abgeschickt", true);
                    }
                    //array_push($this->_data["studiengaenge"], $studiengang);
                }
            }
		}

        $this->setRawData("prestudentStatus", $prestudentStatus);

		/*
		if(count($this->_data["studiengaenge"]) > 1)
		{
			usort($this->_data["studiengaenge"], array($this, "cmpStg"));
		}*/

        //load Dokumente from Studiengang
        $dokumenteStudiengang = array();

        foreach($this->getData('studiengaenge') as $stg)
        {
            $dokumenteStudiengang[$stg->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($stg->studiengang_kz, true, true)->retval;
        }

        $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

        //load dokumente
        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        //load dokumente
        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        //adding abschlusszeugnis if it is not present in dokumente
        if(!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
        {
            $akten = $this->AkteModel->getAktenAccepted();

            if (hasData($akten))
            {
                if (isset($akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
                {
                    $dok = $akten->retval[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]];
                    $dokumente = $this->getData('dokumente');
                    $dokumente[$dok->dokument_kurzbz] = $dok;
                    $this->setRawData('dokumente', $dokumente);
                }
            }
        }

        $this->_getPersonalDocuments();

        $this->setRawData("completenessError", $this->_checkDataCompleteness());


		//load prestudent data for correct studiengang
		foreach ($this->getData("prestudent") as $prestudent)
		{
			//load studiengaenge der prestudenten
			if($prestudent->studiengang_kz == $studiengang_kz)
			{
				//$prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
				if (($prestudent->status_kurzbz === "Interessent"
					|| $prestudent->status_kurzbz === "Bewerber"))
				{
					//$studienplan = $this->_loadStudienplan($prestudentStatus->studienplan_id);
					//$this->_data["studiengang"]->studienplan = $studienplan;


					if((!empty($this->getData("completenessError")["person"]))
							|| (!empty($this->getData("completenessError")["adresse"]))
							|| (!empty($this->getData("completenessError")["kontakt"]))
							|| (!empty($this->getData("completenessError")["dokumente"][$prestudent->studiengang_kz]))
							|| (!empty($this->getData("completenessError")["doks"])))
					{
						$this->_setError(true, $this->lang->line("send_datenUnvollstaendig"));
						$this->load->view('send', $this->getAllData());
					}
					else
					{
						$dokument_kurzbz_array = array();
						if(($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
						{
							array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["reisepass"]);
						}

						if(($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
						{
							array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["lebenslauf"]);
						}

						if(($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]])))
						{
							array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]);
						}

						if(($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->getData("studiengang")->typ]])))
						{
							array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->getData("studiengang")->typ]);
						}

						if(is_null($prestudent->bewerbung_abgeschicktamum))
						{
							if(($this->DokumentPrestudentModel->setAccepted($prestudent->prestudent_id, $prestudent->studiengang_kz)->retval === true) && ($this->DokumentPrestudentModel->setAcceptedDocuments($prestudent->prestudent_id, $prestudent->studiengang_kz, $dokument_kurzbz_array)->retval === true))
							{
                                $prestudentStatus = array();
                                $prestudentStatus['prestudent_id'] = $prestudent->prestudent_id;
                                $prestudentStatus['status_kurzbz'] = "Interessent";
                                $prestudentStatus['rt_stufe'] = $prestudent->rt_stufe;
                                $prestudentStatus['studiensemester_kurzbz'] = $prestudent->studiensemester_kurzbz;
                                $prestudentStatus['orgform_kurzbz'] = $prestudent->orgform_kurzbz;
                                $prestudentStatus['studienplan_id'] = $prestudent->studienplan_id;
                                $prestudentStatus['bewerbung_abgeschicktamum'] = date('Y-m-d H:i:s');
                                $prestudentStatus['datum'] = date('Y-m-d');
                                $prestudentStatus['ausbildungssemester'] = $prestudent->ausbildungssemester;

                                $this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);

                                $studiengang = $this->getData("studiengang");
                                $studiengang->studiengangstyp = $this->StudiengangstypModel->getStudiengangstyp($studiengang->typ)->retval;

								$this->_sendMessageMailApplicationConfirmation($this->getData("person"), $studiengang);
								$this->_sendMessageMailNewApplicationInfo($this->getData("person"), $studiengang);

                                if($this->getData('error') !== null)
                                {
                                    $this->load->view('send', $this->getAllData());
                                }

								$time = time();
								redirect("/Aufnahmetermine?send=".$time);
							}
							else
							{
								$this->_setError(true, $this->lang->line("send_FehlerDokumente"));
								$this->load->view('send', $this->getAllData());
							}
						}
						else
						{
							$this->_setError(true, $this->lang->line("send_bereitsAbgeschickt"));
							$this->load->view('send', $this->getAllData());
						}
					}
				}
			}
		}
	}

    /**
     *
     */
    private function _getPersonalDocuments()
    {
        $personalDocumentsArray = array();

        if (isSuccess($reisepass = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["reisepass"])))
        {
            $personalDocumentsArray[$this->config->item("dokumentTypen")["reisepass"]] = $reisepass->retval;
        }

        if (isSuccess($lebenslauf = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["lebenslauf"])))
        {
            $personalDocumentsArray[$this->config->item("dokumentTypen")["lebenslauf"]] = $lebenslauf->retval;
        }

        $this->setData('personalDocuments', success($personalDocumentsArray));
    }

    /**
     * @param $bool
     * @param null $msg
     */
    private function _setError($bool, $msg = null)
    {
        $error = new stdClass();
        $error->error = $bool;
        $error->msg = $msg;

        $this->setRawData('error', $error);
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

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->getData("sprache");

		/*
        $messageArray = array(
            "vorlage_kurzbz" => 'MailApplicationConfirmation',
            "oe_kurzbz" => $oe,
            "data" => $data,
            "sprache" => ucfirst($sprache),
            "orgform_kurzbz" => $orgform_kurzbz,
            "relationmessage_id" => null,
            "multiPartMime" => false,
            'receiver_id' => $person->person_id
        );*/

		$message = $this->MessageModel->sendMessageVorlage('MailApplicationConfirmation', $oe, $data, $sprache, $orgform_kurzbz, null, false, $person->person_id);

		if(hasData($message))
		{
				//success
		}
		else
		{
			$this->setData("message", '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />');
            $this->_setError(true, 'Could not send message'." ".$message->fhcCode." ".(isset($message->msg) ? $message->msg : $message->retval));
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

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->getData("sprache");
/*
        $messageArray = array(
            "vorlage_kurzbz" => 'MailNewApplicationInfo',
            "oe_kurzbz" => $oe,
            "data" => $data,
            "sprache" => ucfirst($sprache),
            "orgform_kurzbz" => $orgform_kurzbz,
            "relationmessage_id" => null,
            "multiPartMime" => false,
            'receiver_id' => $person->person_id
        );*/

		$message = $this->MessageModel->sendMessageVorlage('MailNewApplicationInfo', $oe, $data, $sprache, $orgform_kurzbz, null, false, $person->person_id);

        if(hasData($message))
        {
            //success
        }
        else
        {
            $this->setData("message", '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />');
            $this->_setError(true, 'Could not send message'." ".$message->fhcCode." ".(isset($message->msg) ? $message->msg : $message->retval));
        }
	}

	private function _checkDataCompleteness()
	{
		$error = array("dokumente"=>array(), "person"=>array(), "adresse"=>array(), "kontakt"=>array(), "doks"=>array());

        $abschlusszeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["abschlusszeugnis_".$this->getData("studiengang")->typ])->retval;
        $letztesZeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"])->retval;
		
		//check documents
		foreach($this->getData("dokumenteStudiengang") as $key=>$doks)
		{
			foreach($doks as $dokType)
			{
				if((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$dokType->dokument_kurzbz])) && ($dokType->pflicht == true)))
				{
					$error["dokumente"][$key][$dokType->bezeichnung] = $dokType;
				}
			}
			
			foreach($this->getData("personalDocuments") as $dokType)
			{
				if(($this->getData('dokumente') !== null) && ((!isset($this->getData("dokumente")[$dokType->dokument_kurzbz]))))
				{
					$error["dokumente"][$key][$dokType->bezeichnung] = $dokType;
				}
			}

			if((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz])) || ((!$this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->dms_id == null ))))
			{
				$error["dokumente"][$key][$abschlusszeugnis->bezeichnung] = $abschlusszeugnis;
			}
			elseif((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$letztesZeugnis->dokument_kurzbz])) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht)))
			{
				$error["dokumente"][$key][$letztesZeugnis->bezeichnung] = $letztesZeugnis;
			}
		}

		//check personal data
		$person = $this->getData("person");

		if($person !== null)
        {

            if ($person->vorname == "")
            {
                $error["person"]["vorname"] = true;
            }

            if ($person->nachname == "")
            {
                $error["person"]["nachname"] = true;
            }

            if ($person->gebdatum == null)
            {
                $error["person"]["geburtsdatum"] = true;
            }

            if (($person->gebort == null) || ($person->gebort == ""))
            {
                $error["person"]["geburtsort"] = true;
            }

            if (($person->geburtsnation == null) || ($person->geburtsnation == ""))
            {
                $error["person"]["geburtsnation"] = true;
            }

            if (($person->staatsbuergerschaft == null) || ($person->staatsbuergerschaft == ""))
            {
                $error["person"]["staatsbuergerschaft"] = true;
            }

            if ((($person->svnr == null) || ($person->svnr == "")) && ($person->geburtsnation == "A"))
            {
                $error["person"]["svnr"] = true;
            }

            if (($person->geschlecht == null) || ($person->geschlecht == ""))
            {
                $error["person"]["geschlecht"] = true;
            }
        }
        else
        {
            $error["person"]["vorname"] = true;
            $error["person"]["nachname"] = true;
            $error["person"]["geburtsdatum"] = true;
            $error["person"]["geburtsort"] = true;
            $error["person"]["geburtsnation"] = true;
            $error["person"]["staatsbuergerschaft"] = true;
            $error["person"]["svnr"] = true;
            $error["person"]["geschlecht"] = true;
        }

		//check adress data
		if($this->getData('adresse') !== null)
		{
			$adresse = $this->getData("adresse");

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
		$kontakt = $this->getData("kontakt");

		if($kontakt !== null)
        {

            if ((!isset($kontakt["telefon"])) || ($kontakt["telefon"]->kontakt == ""))
            {
                $error["kontakt"]["telefon"] = true;
            }

            if ((!isset($kontakt["email"])) || ($kontakt["email"]->kontakt == ""))
            {
                $error["kontakt"]["email"] = true;
            }
        }
        else
        {
            $error["kontakt"]["telefon"] = true;
            $error["kontakt"]["email"] = true;
        }

		if(($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
		{
			$error["doks"]["lebenslauf"] = true;
		}

		if(($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
		{
			$error["doks"]["reisepass"] = true;
		}

		return $error;
	}
}
