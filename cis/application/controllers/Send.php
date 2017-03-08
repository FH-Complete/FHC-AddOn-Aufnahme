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
        if ((isset($this->input->get()["studiengang_kz"]) && ($this->input->get()["studiengang_kz"] !== null) && ($this->input->get()["studiengang_kz"] !== ''))
            && (isset($this->input->get()['studienplan_id'])) && ($this->input->get()["studienplan_id"] !== null) && ($this->input->get()["studienplan_id"] !== '')
        )
        {
            $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
            $this->setData('person', $this->PersonModel->getPerson());

            $this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
            $this->setRawData("studienplan_id", $this->input->get("studienplan_id"));

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

            //setting selected Studiengang by GET Param
            $abgeschickt_array = array();
            $studiengaenge = array();

            foreach($this->getData("studiengaenge") as $stg)
            {
                if((count($stg->prestudenten) > 1) && (count($stg->prestudentstatus) > 1))
                {
                    foreach($stg->prestudenten as $key => $ps)
                    {
                        $tempStg = clone $stg;
                        $tempStg->prestudenten = array();
                        $tempStg->prestudenten[0] = $ps;
                        $tempStg->prestudentstatus = array();
                        $tempStg->prestudentstatus[0] = $stg->prestudentstatus[$key];
                        $tempStg->studienplaene = array();
                        $tempStg->studienplaene[0] = $stg->studienplaene[$key];
                        array_push($studiengaenge, $tempStg);

                        if ($tempStg->studiengang_kz === $this->getData('studiengang_kz') && ($tempStg->prestudentstatus[0]->studienplan_id === $this->getData("studienplan_id")))
                        {
                            $this->setRawData("studiengang", $tempStg);
                        }

                        if ($tempStg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                        {
                            $this->setRawData("bewerbung_abgeschickt", true);
                            $abgeschickt_array[$tempStg->studiengang_kz] = true;
                        }
                    }
                }
                else
                {
                    array_push($studiengaenge, $stg);
                    if ($stg->studiengang_kz === $this->getData('studiengang_kz') && ($stg->prestudentstatus[0]->studienplan_id === $this->getData("studienplan_id")))
                    {
                        $this->setRawData("studiengang", $stg);
                    }
                }
            }

            $this->setRawData("studiengaenge", $studiengaenge);
            $this->setRawData('abgeschickt_array', $abgeschickt_array);

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

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

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            //load data for specialization
            $spezialisierung = array();
            $spezialisierung[$this->getData('prestudent')->studiengang_kz] = $this->PrestudentModel->getSpecialization($this->getData('prestudent')->prestudent_id, true)->retval;
            $this->setRawData('spezialisierung', $spezialisierung);

            //load Dokumente from Studiengang
            $dokumenteStudiengang = array();
            $dokumenteStudiengang[$this->getData('studiengang')->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($this->getData('studiengang')->studiengang_kz, true, true)->retval;
            $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

            //load phrase for specialization
            $spezPhrase = array();
            $spezPhrase[$this->getData('prestudent')->studiengang_kz] = $this->getPhrase("Aufnahme/Spezialisierung", $this->getData('sprache'), $this->getData('studiengang')->oe_kurzbz, $this->getData('studiengang')->studienplaene[0]->orgform_kurzbz);
            $this->setRawData('spezPhrase', $spezPhrase);

            //load dokumente
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            //adding abschlusszeugnis if it is not present in dokumente
            if (!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
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
        else
        {
            redirect("/Bewerbung");
        }
    }


    /**
     *
     * @param unknown $studiengang_kz
     * @param unknown $studienplan_id
     */
    public function send($studiengang_kz, $studienplan_id)
    {
        if ((($studiengang_kz !== null) && ($studiengang_kz !== ''))
            && (($studienplan_id !== null) && ($studienplan_id !== ''))
        )
        {
            $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
            $this->setData('person', $this->PersonModel->getPerson());

            $this->setRawData("studiengang_kz", $studiengang_kz);
            $this->setRawData("studienplan_id", $studienplan_id);

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

            //setting selected Studiengang by GET Param
            $abgeschickt_array = array();
            $studiengaenge = array();

            foreach($this->getData("studiengaenge") as $stg)
            {
                if((count($stg->prestudenten) > 1) && (count($stg->prestudentstatus) > 1))
                {
                    foreach($stg->prestudenten as $key => $ps)
                    {
                        $tempStg = clone $stg;
                        $tempStg->prestudenten = array();
                        $tempStg->prestudenten[0] = $ps;
                        $tempStg->prestudentstatus = array();
                        $tempStg->prestudentstatus[0] = $stg->prestudentstatus[$key];
                        $tempStg->studienplaene = array();
                        $tempStg->studienplaene[0] = $stg->studienplaene[$key];
                        array_push($studiengaenge, $tempStg);

                        if ($tempStg->studiengang_kz === $this->getData('studiengang_kz') && ($tempStg->prestudentstatus[0]->studienplan_id === $this->getData("studienplan_id")))
                        {
                            $this->setRawData("studiengang", $tempStg);
                        }

                        if ($tempStg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                        {
                            $this->setRawData("bewerbung_abgeschickt", true);
                            $abgeschickt_array[$tempStg->studiengang_kz] = true;
                        }
                    }
                }
                else
                {
                    array_push($studiengaenge, $stg);
                    if ($stg->studiengang_kz === $this->getData('studiengang_kz') && ($stg->prestudentstatus[0]->studienplan_id === $this->getData("studienplan_id")))
                    {
                        $this->setRawData("studiengang", $stg);
                    }
                }
            }

            $this->setRawData("studiengaenge", $studiengaenge);
            $this->setRawData('abgeschickt_array', $abgeschickt_array);

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

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

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            //load data for specialization
            $spezialisierung = array();
            $spezialisierung[$this->getData('prestudent')->studiengang_kz] = $this->PrestudentModel->getSpecialization($this->getData('prestudent')->prestudent_id, true)->retval;
            $this->setRawData('spezialisierung', $spezialisierung);

            //load Dokumente from Studiengang
            $dokumenteStudiengang = array();
            $dokumenteStudiengang[$this->getData('studiengang')->studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($this->getData('studiengang')->studiengang_kz, true, true)->retval;
            $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

            //load phrase for specialization
            $spezPhrase = array();
            $spezPhrase[$this->getData('prestudent')->studiengang_kz] = $this->getPhrase("Aufnahme/Spezialisierung", $this->getData('sprache'), $this->getData('studiengang')->oe_kurzbz, $this->getData('studiengang')->studienplaene[0]->orgform_kurzbz);
            $this->setRawData('spezPhrase', $spezPhrase);


            //load dokumente
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            //adding abschlusszeugnis if it is not present in dokumente
            if (!isset($this->getData('dokumente')[$this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]]))
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

            $prestudent = $this->getData('prestudent');
            $prestudentStatus = $this->getData('prestudentStatus');

            //load studiengaenge der prestudenten
            if ($this->getData('prestudent')->studiengang_kz == $studiengang_kz)
            {
                if (($this->getData('prestudentStatus')->status_kurzbz === "Interessent"
                    || $this->getData('prestudentStatus')->status_kurzbz === "Bewerber")
                )
                {
                    if ((!empty($this->getData("completenessError")["person"]))
                        || (!empty($this->getData("completenessError")["adresse"]))
                        || (!empty($this->getData("completenessError")["kontakt"]))
                        || (!empty($this->getData("completenessError")["dokumente"][$this->getData('prestudent')->studiengang_kz]))
                        || (!empty($this->getData("completenessError")["doks"]))
                    )
                    {
                        $this->_setError(true, $this->lang->line("send_datenUnvollstaendig"));
                        $this->load->view('send', $this->getAllData());
                    }
                    else
                    {
                        $dokument_kurzbz_array = array();
                        if (($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
                        {
                            array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["reisepass"]);
                        }

                        if (($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
                        {
                            array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["lebenslauf"]);
                        }

                        if (($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]])))
                        {
                            array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]);
                        }

                        if (($this->getData('dokumente') !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $this->getData("studiengang")->typ]])))
                        {
                            array_push($dokument_kurzbz_array, $this->config->config["dokumentTypen"]["abschlusszeugnis_" . $this->getData("studiengang")->typ]);
                        }

                        if (is_null($this->getData('prestudentStatus')->bewerbung_abgeschicktamum))
                        {
                            if (($this->DokumentPrestudentModel->setAccepted($this->getData('prestudent')->prestudent_id, $this->getData('prestudent')->studiengang_kz)->retval === true) && ($this->DokumentPrestudentModel->setAcceptedDocuments($this->getData('prestudent')->prestudent_id, $this->getData('prestudent')->studiengang_kz, $dokument_kurzbz_array)->retval === true))
                            {
                                $prestudentStatus = array();
                                $prestudentStatus['prestudent_id'] = $this->getData('prestudent')->prestudent_id;
                                $prestudentStatus['status_kurzbz'] = "Interessent";
                                $prestudentStatus['rt_stufe'] = $this->getData('prestudentStatus')->rt_stufe;
                                $prestudentStatus['studiensemester_kurzbz'] = $this->getData('prestudentStatus')->studiensemester_kurzbz;
                                $prestudentStatus['orgform_kurzbz'] = $this->getData('prestudentStatus')->orgform_kurzbz;
                                $prestudentStatus['studienplan_id'] = $this->getData('prestudentStatus')->studienplan_id;
                                $prestudentStatus['bewerbung_abgeschicktamum'] = date('Y-m-d H:i:s');
                                $prestudentStatus['datum'] = date('Y-m-d');
                                $prestudentStatus['ausbildungssemester'] = $this->getData('prestudentStatus')->ausbildungssemester;

                                $this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);

                                $studiengang = $this->getData("studiengang");
                                $studiengang->studiengangstyp = $this->StudiengangstypModel->getStudiengangstyp($studiengang->typ)->retval;

                                $this->_sendMessageMailApplicationConfirmation($this->getData("person"), $studiengang, $studienplan_id);
                                $this->_sendMessageMailNewApplicationInfo($this->getData("person"), $studiengang, $studienplan_id);

                                if ($this->getData('error') !== null)
                                {
                                    $this->load->view('send', $this->getAllData());
                                }

                                $time = time();
                                redirect("/Aufnahmetermine?send=" . $time);
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
        else
        {
            redirect('/Bewerbung');
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

    private function _sendMessageMailApplicationConfirmation($person, $studiengang, $studienplan_id)
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

        foreach($studiengang->studienplaene as $stpl)
        {
            if($stpl->studienplan_id == $studienplan_id)
            {
                $orgform_kurzbz = $stpl->orgform_kurzbz;
            }
        }

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

        $message = $this->MessageModel->sendMessageVorlage('MailApplicationConfirmation', $oe, $data, $sprache, $orgform_kurzbz, null, true, $person->person_id);

        if (hasData($message))
        {
            //success
        }
        else
        {
            $this->setData("message", '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />');
            $this->_setError(true, 'Could not send message' . " " . $message->fhcCode . " " . (isset($message->msg) ? $message->msg : $message->retval));
        }
    }


    private function _sendMessageMailNewApplicationInfo($person, $studiengang, $studienplan_id)
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

        foreach($studiengang->studienplaene as $stpl)
        {
            if($stpl->studienplan_id == $studienplan_id)
            {
                $orgform_kurzbz = $stpl->orgform_kurzbz;
            }
        }

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

        $message = $this->MessageModel->sendMessageVorlage('MailNewApplicationInfo', $oe, $data, $sprache, $orgform_kurzbz, null, true, $person->person_id);

        if (hasData($message))
        {
            //success
        }
        else
        {
            $this->setData("message", '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />');
            $this->_setError(true, 'Could not send message' . " " . $message->fhcCode . " " . (isset($message->msg) ? $message->msg : $message->retval));
        }
    }

    private function _checkDataCompleteness()
    {
        $error = array("dokumente" => array(), "person" => array(), "adresse" => array(), "kontakt" => array(), "doks" => array(), "spezialisierung" => array());

        $abschlusszeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["abschlusszeugnis_" . $this->getData("studiengang")->typ])->retval;
        $letztesZeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"])->retval;

        //check documents

        foreach ($this->getData("dokumenteStudiengang") as $key => $doks)
        {
            foreach ($doks as $dokType)
            {
                if ((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$dokType->dokument_kurzbz])) && ($dokType->pflicht == true)))
                {
                    $error["dokumente"][$key][$dokType->bezeichnung] = $dokType;
                }
            }

            foreach ($this->getData("personalDocuments") as $dokType)
            {
                if (($this->getData('dokumente') !== null) && ((!isset($this->getData("dokumente")[$dokType->dokument_kurzbz]))))
                {
                    $error["dokumente"][$key][$dokType->bezeichnung] = $dokType;
                }
            }

            if ((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz])) || ((!$this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->dms_id == null))))
            {
                $error["dokumente"][$key][$abschlusszeugnis->bezeichnung] = $abschlusszeugnis;
            }
            elseif ((($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$letztesZeugnis->dokument_kurzbz])) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht)))
            {
                $error["dokumente"][$key][$letztesZeugnis->bezeichnung] = $letztesZeugnis;
            }

            $spezialisierung = $this->getData('spezialisierung');
            $spezPhrase = $this->getData('spezPhrase');

            if (isset($spezPhrase) && (isset($spezPhrase[$key])) && ($spezPhrase[$key] !== null) && ((substr($spezPhrase[$key], 0, 3) !== '<i>')))
            {
                if ((!isset($spezialisierung)) || (!isset($spezialisierung[$key])) || (empty($spezialisierung[$key])))
                {
                    $error['spezialisierung'][$key] = true;
                }
            }
        }

        //check personal data
        $person = $this->getData("person");

        if ($person !== null)
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
        if ($this->getData('adresse') !== null)
        {
            $adresse = $this->getData("adresse");

            if (($adresse->strasse == null) || ($adresse->strasse == ""))
            {
                $error["adresse"]["strasse"] = true;
            }

            if (($adresse->plz == null) || ($adresse->plz == ""))
            {
                $error["adresse"]["plz"] = true;
            }

            if (($adresse->ort == null) || ($adresse->ort == ""))
            {
                $error["adresse"]["ort"] = true;
            }
        }
        else
        {
            $error["adresse"]['strasse'] = true;
            $error["adresse"]['plz'] = true;
            $error["adresse"]['ort'] = true;
        }

        //check contact data
        $kontakt = $this->getData("kontakt");

        if ($kontakt !== null)
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

        if (($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
        {
            $error["doks"]["lebenslauf"] = true;
        }

        if (($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
        {
            $error["doks"]["reisepass"] = true;
        }

        return $error;
    }

    function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null)
    {
        $result = null;
        $phrasen = null;

        if (isset($this->session->userdata()['Phrase.getPhrasen:' . $sprache]))
        {
            $result = $this->session->userdata()['Phrase.getPhrasen:' . $sprache];
        }

        if (hasData($result))
        {
            $phrasen = $result->retval;

            if (is_array($phrasen))
            {
                $text = "";
                $sprache = ucfirst($sprache);

                foreach ($phrasen as $p)
                {
                    if ($p->phrase == $phrase)
                    {
                        if (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                            {
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            }
                            else
                            {
                                $text = $p->text;
                            }
                        }
                        elseif (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                            {
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            }
                            else
                            {
                                $text = $p->text;
                            }
                        }
                        elseif (($p->orgeinheit_kurzbz == $this->config->item("root_oe")) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                            {
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            }
                            else
                            {
                                $text = $p->text;
                            }
                        }
                        elseif (($p->orgeinheit_kurzbz == null) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                            {
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            }
                            else
                            {
                                $text = $p->text;
                            }
                        }
                    }
                }

                if ($text != "")
                {
                    return $text;
                }

                if ($this->config->item('display_phrase_name'))
                {
                    return "<i>[$phrase]</i>";
                }
            }
            else
            {
                return $phrasen;
            }
        }
        else
        {
            return "Please load phrases first";
        }
    }
}
