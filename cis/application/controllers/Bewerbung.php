<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Bewerbung extends UI_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$this->load->library('form_validation');
		
		// 
		$currentLanguage = $this->getCurrentLanguage();
		if (hasData($currentLanguage))
		{
			$this->setData('sprache', $currentLanguage);
			$this->lang->load(array('aufnahme', 'login'), $this->getData('sprache'));
		}
		
		// 
		$this->load->helper('form');
		
		// Loading the 
		$this->load->model('system/Phrase_model', 'PhraseModel');
		
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studienplan_model', 'StudienplanModel');
		
		$this->load->model('person/Adresse_model', 'AdresseModel');
		$this->load->model('person/Kontakt_model', 'KontaktModel');
		$this->load->model('person/Person_model', 'PersonModel');
		
		$this->load->model('crm/Akte_model', 'AkteModel');
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');
		$this->load->model('crm/Dokument_model', 'DokumentModel');
        $this->load->model('crm/Bewerbungstermine_model', 'BewerbungstermineModel');
        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');
		
		$this->load->model('content/Dms_model', 'DmsModel');
		
		$this->load->model('codex/Gemeinde_model', 'GemeindeModel');
		$this->load->model('codex/Nation_model', 'NationModel');
		$this->load->model('codex/Bundesland_model', 'BundeslandModel');
		
		$this->load->model('system/Message_model', 'MessageModel');

		$this->setRawData("udfs", $this->getUDFs());
	}
	
	/**
	 * 
	 */
	public function index()
	{
		$this->PhraseModel->getPhrasen(
			'aufnahme',
			ucfirst($this->getData('sprache'))
		);

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
        }

        if ((isset($this->input->get()["studiengang_kz"]) && ($this->input->get()["studiengang_kz"] !== null) && ($this->input->get()["studiengang_kz"] !== ''))
            && (isset($this->input->get()['studienplan_id'])) && ($this->input->get()["studienplan_id"] !== null) && ($this->input->get()["studienplan_id"] !== '')
        )
        {
            $this->setRawData("studiengang_kz", $this->input->get("studiengang_kz"));
            $this->setRawData("studienplan_id", $this->input->get("studienplan_id"));

            redirect("/Bewerbung/studiengang/".$this->getData('studiengang_kz')."/".$this->getData('studienplan_id')."/".$this->getData('studiensemester')->studiensemester_kurzbz);
        }
		
		$this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
		
		$this->setData('person', $this->PersonModel->getPerson());

        $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
            $this->getData('studiensemester')->studiensemester_kurzbz,
            '',
            'Interessent',
            true
        ));

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
                }
            }
            else
            {
                array_push($studiengaenge, $stg);
            }
        }

        $this->setRawData("studiengaenge", $studiengaenge);

		$this->setData(
		    'prestudent',
            $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                null,
                true
            ));

		$this->_isAnyApplicationSent();

		$this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);
		
		$this->setData('adresse', $this->AdresseModel->getAdresse());
		
		$this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());
		
		$this->setData('nationen', $this->NationModel->getAll());
		
		$this->setData('bundeslaender', $this->BundeslandModel->getAll());
		
		$this->setData('gemeinden', $this->GemeindeModel->getGemeinde());
		
		$this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());
		
		$this->_getPersonalDocuments();
		
		$this->_missingData();
		
		// Form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
		$this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
		$this->form_validation->set_rules("gebdatum", "Geburtsdatum", "callback_check_date");
		$this->form_validation->set_rules("email", "E-Mail", "required|valid_email");

        foreach ($this->getData("gemeinden") as $gemeinde)
        {
            if (($this->getData("adresse") !== null) && ($gemeinde->plz == $this->getData("adresse")->plz) && ($gemeinde->name == $this->getData("adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("adresse")->ort))
            {
                $this->setRawData("ort_dd", $gemeinde->gemeinde_id);
            }
            if (($this->getData("zustell_adresse") !== null) && ($gemeinde->plz == $this->getData("zustell_adresse")->plz) && ($gemeinde->name == $this->getData("zustell_adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("zustell_adresse")->ort))
            {
                $this->setRawData("zustell_ort_dd", $gemeinde->gemeinde_id);
            }
        }

        $this->load->view('bewerbung', $this->getAllData());

	}

    /**
     *
     * @return unknown
     */
    public function check_date()
    {
        $date = explode(".", $this->input->post("gebdatum"));
        if ((is_array($date)) && (count($date) == 3) && (!checkdate($date[1], $date[0], $date[2])))
        {
            //$this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
            $this->form_validation->set_message("check_date", "Bitte geben Sie ein gültiges Datum an.");
            return false;
        }
        return true;
    }

    private function _checkDataCompleteness()
    {
        $complete = array("person" => true, "adresse" => true, "kontakt" => true, "zustelladresse" => true, "dokumente" => true, "requirements_dokumente"=>true,"spezialisierung"=>true);
        //check personal data
        $person = $this->getData("person");
        if ($person->vorname == "")
        {
            $complete["person"] = false;
        }
        if ($person->nachname == "")
        {
            $complete["person"] = false;
        }
        if ($person->gebdatum == null)
        {
            $complete["person"] = false;
        }
        if (($person->gebort == null) || ($person->gebort == ""))
        {
            $complete["person"] = false;
        }
        if (($person->geburtsnation == null) || ($person->geburtsnation == ""))
        {
            $complete["person"] = false;
        }
        if (($person->staatsbuergerschaft == null) || ($person->staatsbuergerschaft == ""))
        {
            $complete["person"] = false;
        }
        if ((($person->svnr == null) || ($person->svnr == "")) && ($person->geburtsnation == "A"))
        {
            $complete["person"] = false;
        }
        //check adress data

        if ($this->getData("adresse") !== null)
        {
            $adresse = $this->getData("adresse");
            if (($adresse->strasse == null) || ($adresse->strasse == ""))
            {
                $complete["adresse"] = false;
            }
            if (($adresse->plz == null) || ($adresse->plz == ""))
            {
                $complete["adresse"] = false;
            }
            if (($adresse->ort == null) || ($adresse->ort == ""))
            {
                $complete["adresse"] = false;
            }
        }
        else
        {
            $complete['adresse'] = false;
        }
        //check adress data
        if ($this->getData("zustell_adresse") !== null)
        {
            $adresse = $this->getData("zustell_adresse");
            if (($adresse->strasse == null) || ($adresse->strasse == ""))
            {
                $complete["zustelladresse"] = false;
            }
            if (($adresse->plz == null) || ($adresse->plz == ""))
            {
                $complete["zustelladresse"] = false;
            }
            if (($adresse->ort == null) || ($adresse->ort == ""))
            {
                $complete["zustelladresse"] = false;
            }
        }
        elseif(($this->getData('adresse') !== null) && ($this->getData('adresse')->zustelladresse === false))
        {
            $complete["zustelladresse"] = false;
        }

        //check contact data
        $kontakt = $this->getData("kontakt");
        if($kontakt !== null)
        {
            if ((!isset($kontakt["telefon"])) || ($kontakt["telefon"]->kontakt == ""))
            {
                $complete["kontakt"] = false;
            }
            if ((!isset($kontakt["email"])) || ($kontakt["email"]->kontakt == ""))
            {
                $complete["kontakt"] = false;
            }
        }
        else
        {
            $complete["kontakt"] = false;
        }
        //check documents
        if (($this->getData("dokumente") != null ) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
        {
            $complete["dokumente"] = false;
        }
        if (($this->getData("dokumente") != null ) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
        {
            $complete["dokumente"] = false;
        }
        if ($this->getData("dokumenteStudiengang") !== null)
        {
            foreach ($this->getData("dokumenteStudiengang") as $key => $doks)
            {
                foreach ($doks as $dokType)
                {
                    if (($this->getData("dokumente") !== null) && (!isset($this->getData("dokumente")[$dokType->dokument_kurzbz])) && ($dokType->pflicht == true))
                    {
                        $complete["requirements_dokumente"] = false;
                    }
                }

                foreach ($this->getData("personalDocuments") as $dokType)
                {
                    if ((!isset($this->getData("dokumente")[$dokType->dokument_kurzbz])))
                    {
                        $complete["requirements_dokumente"] = false;
                    }
                }
                $abschlusszeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["abschlusszeugnis_".$this->getData("studiengang")->typ])->retval;
                $letztesZeugnis = $this->DokumentModel->getDokument($this->config->item("dokumentTypen")["letztGueltigesZeugnis"])->retval;
                if ((!isset($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz])) || ((!$this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->dms_id == null )))
                {
                    $complete["requirements_dokumente"] = false;
                }
                elseif ((!isset($this->getData("dokumente")[$letztesZeugnis->dokument_kurzbz])) && ($this->getData("dokumente")[$abschlusszeugnis->dokument_kurzbz]->nachgereicht))
                {
                    $complete["requirements_dokumente"] = false;
                }
            }
        }
        else
        {
            $complete["requirements_dokumente"] = false;
        }

        $spezPhrase = $this->getData('spezPhrase');
        $spezialisierung = $this->getData('spezialisierung');

        if(
            isset($spezPhrase)
            && (isset($spezPhrase[$this->getData('studiengang')->studiengang_kz]))
            && (substr($spezPhrase[$this->getData('studiengang')->studiengang_kz], 0, 3) !== '<i>'))
        {
            if((!isset($spezialisierung)) || (!isset($spezialisierung[$this->getData('studiengang')->studiengang_kz])) || (empty($spezialisierung[$this->getData('studiengang')->studiengang_kz])))
            {
                $complete["spezialisierung"] = false;
            }
        }
        return $complete;
    }

    /*
    public function checkDataCompleteness()
    {
        $studiengang_kz = $this->input->get("studiengang_kz");
        $this->setData('person', $this->PersonModel->getPerson());

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

        foreach ($this->getData('studiengaenge') as $stg)
        {
            if ($stg->studiengang_kz === $studiengang_kz)
            {
                $this->setRawData("studiengang", $stg);
            }
        }

        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

        $this->setData('adresse', $this->AdresseModel->getAdresse());

        $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

        $this->setRawData("studiengaenge", array($this->getData('studiengang')));

        $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
        $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

        $spezPhrase = array();
        foreach($this->getData('studiengaenge') as $stg)
        {
            if($stg->studiengang_kz === $studiengang_kz)
            {
                 $spezPhrase[$studiengang_kz] = $this->getPhrase("Aufnahme/Spezialisierung", $this->getData('sprache'), $stg->oe_kurzbz, $stg->studienplaene[0]->orgform_kurzbz);
            }
        }
        $this->setRawData('spezPhrase', $spezPhrase);

        //load data for specialization
        $spezialisierung = array();
        $spezialisierung[$this->getData('prestudent')->studiengang_kz] = $this->PrestudentModel->getSpecialization($this->getData('prestudent')->prestudent_id, true)->retval;
        $this->setRawData('spezialisierung', $spezialisierung);

        $dokumenteStudiengang = array();
        $dokumenteStudiengang[$studiengang_kz] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($studiengang_kz, true, true)->retval;

        $this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);

        $this->_getPersonalDocuments();

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

        echo json_encode($this->_checkDataCompleteness());
    }*/

    public function studiengang($studiengang_kz, $studienplan_id, $studiensemester_kurzbz)
    {
        // Form validation rules
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
        $this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
        $this->form_validation->set_rules("gebdatum", "Geburtsdatum", "callback_check_date");
        $this->form_validation->set_rules("email", "E-Mail", "required|valid_email");

        $this->setRawData("studiengang_kz", $studiengang_kz);
        $this->setRawData("studienplan_id", $studienplan_id);

        if ($this->form_validation->run() == FALSE)
        {
            $this->PhraseModel->getPhrasen(
                'aufnahme',
                ucfirst($this->getData('sprache'))
            );

            $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

            $this->setData('person', $this->PersonModel->getPerson());

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
            if($this->getData('studiengaenge') !== null)
            {
                foreach ($this->getData('studiengaenge') as $stg)
                {
                    if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
                    {
                        $this->setRawData("studiengang", $stg);
                    }

                    if ($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
                    {
                        $this->setRawData("bewerbung_abgeschickt", true);
                        $abgeschickt_array[$stg->studiengang_kz] = true;
                    }
                }

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

                            if ($tempStg->studiengang_kz === $this->getData('studiengang_kz') && ($tempStg->prestudentstatus[0]->studienplan_id === $studienplan_id))
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
                        if ($stg->studiengang_kz === $this->getData('studiengang_kz') && ($stg->prestudentstatus[0]->studienplan_id === $studienplan_id))
                        {
                            $this->setRawData("studiengang", $stg);
                        }
                    }
                }

                $this->setRawData("studiengaenge", $studiengaenge);
            }
            $this->setRawData('abgeschickt_array', $abgeschickt_array);

            /**
             * check if studiengang data is not set
             * and load prestudenten without status
             *
             */
            if ($this->getData('studiengang') === null)
            {
                $this->setData("prestudent", $this->PrestudentModel->getPrestudentByPersonId(true));
                $prestudenten = $this->getData('prestudent');
                if($prestudenten !== null)
                {
                    foreach ($prestudenten as $key => $prestudent)
                    {
                        if ($prestudent->studiengang_kz !== $studiengang_kz)
                        {
                            unset($prestudenten[$key]);
                        }
                    }
                }

                if (!empty($prestudenten))
                {
                    //prestudent for stg without status exists
                    $prestudenten = array_values($prestudenten);
                    $this->setRawData('prestudent', $prestudenten[0]);
                }
                else
                {
                    //no prestudent for stg exists
                    $this->setRawData('prestudent', null);
                }
                $this->setRawData('prestudentStatus', null);
            }
            else
            {
                $this->setRawData("studiengaenge", array($this->getData('studiengang')));
                $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
                $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);
                $abgeschickt_array = array();
                if ($this->getData('prestudentStatus')->bewerbung_abgeschicktamum != null)
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                    $abgeschickt_array[$stg->studiengang_kz] = true;
                }
                $this->setRawData('abgeschickt_array', $abgeschickt_array);
            }


            //load Studienplan
            $this->setData("studienplan", $this->StudienplanModel->getStudienplan($studienplan_id));
            $fristen = $this->BewerbungstermineModel->getByStudienplan($studienplan_id)->retval;
            $bewerbungMoeglich = false;
            if (!empty($fristen))
            {
                foreach ($fristen as $frist)
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
                $exitingPrestudent = null;

                if ($this->getData('prestudent') !== null)
                {
                    $prestudent = $this->getData('prestudent');
                    $prestudentStatus = $this->getData('prestudentStatus');

                    if (($prestudentStatus !== null) && ($prestudent->studiengang_kz == $studiengang_kz)
                        && ($prestudentStatus->studienplan_id == $studienplan_id)
                        && ($prestudentStatus->studiensemester_kurzbz == $this->getData("studiensemester")->studiensemester_kurzbz)
                    )
                    {
                        $exists = true;
                        $abgeschickt_array = array();
                        if ($prestudentStatus->bewerbung_abgeschicktamum !== null)
                        {
                            $this->setRawData("bewerbung_abgeschickt", true);
                            $abgeschickt_array[$prestudent->studiengang_kz] = true;
                        }

                        $this->setRawData('abgeschickt_array', $abgeschickt_array);
                        //nothing else to do; same prestudent with status exists
                    }
                    elseif (($prestudent->studiengang_kz == $studiengang_kz)
                        && ($prestudentStatus != null)
                        && ($prestudentStatus->studienplan_id === $studienplan_id)
                        && (
                            ($prestudentStatus->status_kurzbz === "Interessent")
                            || ($prestudentStatus->status_kurzbz === "Bewerber")
                            || ($prestudentStatus->status_kurzbz === "Abgewiesener")
                        )
                    )
                    {
                        $exists = true;
                        //just adding new status
                        $prestudentStatus = array();
                        $prestudentStatus['new'] = true;
                        $prestudentStatus['prestudent_id'] = $prestudent->prestudent_id;
                        $prestudentStatus['status_kurzbz'] = "Interessent";
                        $prestudentStatus['rt_stufe'] = 1;
                        $prestudentStatus['studiensemester_kurzbz'] = $this->getData("studiensemester")->studiensemester_kurzbz;
                        $prestudentStatus['orgform_kurzbz'] = $this->getData('studienplan')->orgform_kurzbz;
                        $prestudentStatus['studienplan_id'] = $studienplan_id;
                        $prestudentStatus['datum'] = date('Y-m-d');

                        $this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
                    }
                    elseif (($prestudent->studiengang_kz == $studiengang_kz) && (empty($prestudentStatus)))
                    {
                        //adding status if prestudent has no status
                        $exists = true;
                        $prestudentStatus = array();
                        $prestudentStatus['new'] = true;
                        $prestudentStatus['prestudent_id'] = $prestudent->prestudent_id;
                        $prestudentStatus['status_kurzbz'] = "Interessent";
                        $prestudentStatus['rt_stufe'] = 1;
                        $prestudentStatus['studiensemester_kurzbz'] = $this->getData("studiensemester")->studiensemester_kurzbz;
                        $prestudentStatus['orgform_kurzbz'] = $this->getData('studienplan')->orgform_kurzbz;
                        $prestudentStatus['studienplan_id'] = $studienplan_id;
                        $prestudentStatus['datum'] = date('Y-m-d');
                        $this->PrestudentStatusModel->savePrestudentstatus($prestudentStatus);
                    }
                }

                if ((!$exists))
                {
                    $prestudent = array();
                    $prestudent["person_id"] = $this->getData('person')->person_id;
                    $prestudent["studiengang_kz"] = $studiengang_kz;
                    $prestudent["aufmerksamdurch_kurzbz"] = 'k.A.';
                    $prestudent["insertamum"] = date('Y-m-d H:i:s');
                    $prestudent["insertvon"] = 'aufnahme';

                    $savedPrestudent = $this->PrestudentModel->savePrestudent($prestudent);

                    if (hasData($savedPrestudent))
                    {
                        $prestudentStatus = array();
                        $prestudentStatus['new'] = true;
                        $prestudentStatus['prestudent_id'] = $savedPrestudent->retval;
                        $prestudentStatus['status_kurzbz'] = "Interessent";
                        $prestudentStatus['rt_stufe'] = 1;
                        $prestudentStatus['studiensemester_kurzbz'] = $this->getData("studiensemester")->studiensemester_kurzbz;
                        $prestudentStatus['orgform_kurzbz'] = $this->getData('studienplan')->orgform_kurzbz;
                        $prestudentStatus['studienplan_id'] = $studienplan_id;
                        $prestudentStatus['datum'] = date('Y-m-d');

                        $this->PrestudentStatusModel->savePrestudentStatus($prestudentStatus);
                    }
                }
            }
            else
            {
                redirect("/Studiengaenge");
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

                        if ($tempStg->studiengang_kz === $this->getData('studiengang_kz') && ($tempStg->prestudentstatus[0]->studienplan_id === $studienplan_id))
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
                    if ($stg->studiengang_kz === $this->getData('studiengang_kz') && ($stg->prestudentstatus[0]->studienplan_id === $studienplan_id))
                    {
                        $this->setRawData("studiengang", $stg);
                    }
                }
            }

            $this->setRawData("studiengaenge", $studiengaenge);

            $this->setRawData('abgeschickt_array', $abgeschickt_array);

            $this->setRawData("studiengaenge", array($this->getData('studiengang')));

            $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
            $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

            //$this->_isAnyApplicationSent();

            $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

            $this->setData('adresse', $this->AdresseModel->getAdresse());

            $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

            $this->setData('nationen', $this->NationModel->getAll());

            $this->setData('bundeslaender', $this->BundeslandModel->getAll());

            $this->setData('gemeinden', $this->GemeindeModel->getGemeinde());

            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            $this->_getPersonalDocuments();

            $this->_missingData();

            foreach ($this->getData("gemeinden") as $gemeinde)
            {
                if (($this->getData("adresse") !== null) && ($gemeinde->plz == $this->getData("adresse")->plz) && ($gemeinde->name == $this->getData("adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("adresse")->ort))
                {
                    $this->setRawData("ort_dd", $gemeinde->gemeinde_id);
                }
                if (($this->getData("zustell_adresse") !== null) && ($gemeinde->plz == $this->getData("zustell_adresse")->plz) && ($gemeinde->name == $this->getData("zustell_adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("zustell_adresse")->ort))
                {
                    $this->setRawData("zustell_ort_dd", $gemeinde->gemeinde_id);
                }
            }

            $this->load->view('bewerbung', $this->getAllData());
        }
        else
        {
            $this->_saveData();
        }
    }
    /**
     *
     * @param unknown $studiengang_kz
     */
    public function storno($studiengang_kz, $studienplan_id)
    {
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
        foreach ($this->getData('studiengaenge') as $stg)
        {
            if ($stg->studiengang_kz === $studiengang_kz)
            {
                $this->setRawData("studiengang", $stg);
            }

            if($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
            {
                $this->setRawData("bewerbung_abgeschickt", true);
                $abgeschickt_array[$stg->studiengang_kz] = true;
            }
        }
        $this->setRawData('abgeschickt_array', $abgeschickt_array);

        $this->setRawData("studiengaenge", array($this->getData('studiengang')));

        $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
        $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

        //load person data
        $this->setData('person', $this->PersonModel->getPerson());

        if($this->getData('prestudent') != null)
        {
            $prestudent = $this->getData('prestudent');
            $prestudentStatus = $this->getData('prestudentStatus');
            if ($prestudent->studiengang_kz === $studiengang_kz)
            {
                if($prestudentStatus->bewerbung_abgeschicktamum == null)
                {
                    $deletedPrestudentStatus = $this->PrestudentStatusModel->removePrestudentStatus((array)$prestudentStatus);
                    redirect("/Bewerbung");
                }
                else
                {
                    //TODO call load data method


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

                    $this->setData('prestudent', $this->PrestudentModel->getPrestudentByPersonId(true));

                    $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

                    $this->setData('adresse', $this->AdresseModel->getAdresse());

                    $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

                    $this->setData('nationen', $this->NationModel->getAll());

                    $this->setData('bundeslaender', $this->BundeslandModel->getAll());

                    $this->setData('', $this->GemeindeModel->getGemeinde());

                    $this->setData('', $this->DmsModel->getAktenAcceptedDms());

                    $this->_getPersonalDocuments();

                    $this->_missingData();

                    //$this->_isAnyApplicationSent();

                    $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
                    $this->_setError(true, $this->lang->line("aufnahme/bewerbungKannNichtGeloeschtWerden"));
                    $this->load->view('bewerbung', $this->getAllData());
                }
            }
            else
            {
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
            $this->setData('person', $this->PersonModel->getPerson());

            //load dokumente
            $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

            if($this->getData("dokumente") !== null)
            {
                foreach ($this->getData("dokumente") as $akte)
                {
                    if ($akte->dms_id != null)
                    {
                        $dms = $this->DmsModel->getDms($akte->dms_id)->retval;
                        $akte->dokument = $dms;
                    }
                }
            }
            foreach ($files as $key => $file)
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
                    switch ($key)
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

                    if($this->getData("dokumente") !== null)
                    {
                       foreach ($this->getData("dokumente") as $akte_temp)
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
                    }
                    $obj->kategorie_kurzbz = "Akte";
                    $type = pathinfo($file["name"][0], PATHINFO_EXTENSION);
                    $data = file_get_contents($file["tmp_name"][0]);
                    $obj->file_content = base64_encode($data);
                    $result = new stdClass();
                    $dms = $this->DmsModel->saveDms((array)$obj);

                    if (hasData($dms))
                    {
                        if ($obj->version >= 0)
                        {
                            $akte->dms_id = $dms->retval->dms_id;
                            $result->dms_id = $akte->dms_id;
                            $akte->person_id = $this->getData("person")->person_id;
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

                            $akteInsertResult = $this->AkteModel->saveAkte((array)$akte);

                            if (hasData($akteInsertResult))
                            {
                                $result->success = true;
                                $result->akte_id = $akteInsertResult->retval;
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

                            $akteInsertResult = $this->AkteModel->saveAkte((array)$akte);

                            if (hasData($akteInsertResult))
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
                        $this->_setError(true, $dms->error);
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
     * @return unknown
     */
    public function deleteDocument()
    {
        $result = new stdClass();
        if((isset($this->input->post()["dms_id"])))
        {
            $dms_id = $this->input->post()["dms_id"];
            $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

            foreach($this->getData("dokumente") as $dok)
            {
                if(($dok->dms_id === $dms_id) && ($dok->accepted == false))
                {
                    $result = $this->DmsModel->deleteDms($dok->dms_id);
                    $result->dokument_kurzbz = $dok->dokument_kurzbz;
                }
//				var_dump($result);
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

    private function _saveData()
    {
        $this->PhraseModel->getPhrasen(
            'aufnahme',
            ucfirst($this->getData('sprache'))
        );

        $studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
        if (hasData($studiensemester))
        {
            $this->setData('studiensemester', $studiensemester);
        }
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

        $this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
            $this->getData('studiensemester')->studiensemester_kurzbz,
            '',
            'Interessent',
            true
        ));

        $this->setData(
            'prestudent',
            $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                null,
                true
            ));

        //$this->_isAnyApplicationSent();

        $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

        $this->setData('adresse', $this->AdresseModel->getAdresse());

        $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

        $this->setData('nationen', $this->NationModel->getAll());

        $this->setData('bundeslaender', $this->BundeslandModel->getAll());

        $this->setData('gemeinden', $this->GemeindeModel->getGemeinde());

        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        $this->_getPersonalDocuments();

        $this->_missingData();

        foreach ($this->getData("gemeinden") as $gemeinde)
        {
            if (($this->getData("adresse") !== null) && ($gemeinde->plz == $this->getData("adresse")->plz) && ($gemeinde->name == $this->getData("adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("adresse")->ort))
            {
                $this->setRawData("ort_dd", $gemeinde->gemeinde_id);
            }
            if (($this->getData("zustell_adresse") !== null) && ($gemeinde->plz == $this->getData("zustell_adresse")->plz) && ($gemeinde->name == $this->getData("zustell_adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("zustell_adresse")->ort))
            {
                $this->setRawData("zustell_ort_dd", $gemeinde->gemeinde_id);
            }
        }

        $post = $this->input->post();
        $person = $this->getData("person");
        $person->vorname = $post["vorname"];
        $person->nachname = $post["nachname"];
        //$person->bundesland_code = $post["bundesland"];
        if (isset($post["gebdatum"]))
        {
            $person->gebdatum = date('Y-m-d', strtotime($post["gebdatum"]));
        }
        $person->gebort = (($post["geburtsort"] != '') && ($post["geburtsort"] != 'null')) ? $post["geburtsort"] : null;
        $person->geburtsnation = (($post["nation"] != '') && ($post["nation"] != 'null')) ? $post["nation"] : null;
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
        $person->staatsbuergerschaft = (($post["staatsbuergerschaft"] != '') && ($post["staatsbuergerschaft"] != 'null')) ? $post["staatsbuergerschaft"] : null;
        // An die SVNR wird v1, v2, v3, etc hinzugefuegt wenn die SVNR bereits vorhanden ist
        // In der Anzeige wird dies herausgefiltert. Deshalb muss beim Speichern der Daten
        // wieder die SVNR mit v1 etc geschickt werden wenn diese nicht geaendert wurde
        if ($post["svnr_orig"] != '' && mb_substr($post["svnr_orig"], 0, 10) == $post["svnr"])
        {
            $person->svnr = $post["svnr_orig"];
        }
        else
        {
            if ($post["svnr"] != '')
            {
                $person->svnr = $post["svnr"];
            }
        }
        $person->titelpre = $post["titelpre"] != '' ? $post["titelpre"] : null;
        $person->titelpost = $post["titelpost"] != '' ? $post["titelpost"] : null;

        $updatePerson = $this->PersonModel->savePerson((array)$person);

        if(!hasData($updatePerson))
        {
            $this->_setError(true, $updatePerson->retval);
        }

        $adresse = new stdClass();
        $zustell_adresse = new stdClass();
        if ($post["adresse_nation"] === "A")
        {
            if (($post["strasse"] != "") && ($post["plz"] != "") && ($post["ort_dd"] != ""))
            {
                if ($this->getData("adresse") !== null)
                {
                    $adresse = $this->getData("adresse");
                }
                else
                {
                    $adresse->person_id = $this->getData("person")->person_id;
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

                foreach ($this->getData("gemeinden") as $gemeinde)
                {
                    if ($gemeinde->gemeinde_id === $post["ort_dd"])
                    {
                        $adresse->gemeinde = $gemeinde->name;
                        $adresse->ort = $gemeinde->ortschaftsname;
                        $person->bundesland_code = $gemeinde->bulacode;
                    }
                }
                $updatePerson = $this->PersonModel->savePerson((array)$person);

                if(!hasData($updatePerson))
                {
                    $this->_setError(true, $updatePerson->retval);
                }

                $updateAdresse = $this->AdresseModel->saveAdresse((array)$adresse);

                if(!isSuccess($updateAdresse))
                {
                    $this->_setError(true, $updateAdresse->retval);
                }
            }
        }
        else
        {
            if (($post["strasse"] != "") && ($post["plz"] != "") && ($post["ort"] != ""))
            {
                if ($this->getData("adresse") !== null)
                {
                    $adresse = $this->getData("adresse");
                }
                else
                {
                    $adresse->person_id = $this->getData("person")->person_id;
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
                $updateAdresse = $this->AdresseModel->saveAdresse((array)$adresse);

                if(!isSuccess($updateAdresse))
                {
                    $this->_setError(true, $updateAdresse->retval);
                }
            }
        }
        if ($post["zustelladresse_nation"] === "A")
        {
            if (($post["zustell_strasse"] != "") && (($post["zustell_plz"] != "") && ($post["zustell_ort_dd"] != "")))
            {
                if ($this->getData("zustell_adresse") !== null)
                {
                    $zustell_adresse = $this->getData("zustell_adresse");
                }
                else
                {
                    $zustell_adresse->person_id = $this->getData("person")->person_id;
                    $zustell_adresse->heimatadresse = false;
                    $zustell_adresse->zustelladresse = true;
                }
                $zustell_adresse->strasse = $post["zustell_strasse"];
                $zustell_adresse->nation = $post["zustelladresse_nation"];
                $zustell_adresse->plz = $post["zustell_plz"];

                $this->GemeindeModel->getGemeindeByPlz($zustell_adresse->plz);

                foreach ($this->getData("gemeinden") as $gemeinde)
                {
                    if ($gemeinde->gemeinde_id === $post["zustell_ort_dd"])
                    {
                        $zustell_adresse->gemeinde = $gemeinde->name;
                        $zustell_adresse->ort = $gemeinde->ortschaftsname;
                    }
                }

                $updateZustellAdresse = $this->AdresseModel->saveZustellAdresse((array)$zustell_adresse);

                if(!isSuccess($updateZustellAdresse))
                {
                    $this->_setError(true, $updateZustellAdresse->retval);
                }

            }
        }
        else
        {
            if (($post["zustell_strasse"] != "") && ((($post["zustell_plz"] != "") && ($post["zustell_ort"] != ""))))
            {
                if ($this->getData("zustell_adresse") !== null)
                {
                    $zustell_adresse = $this->getData("zustell_adresse");
                }
                else
                {
                    $zustell_adresse->person_id = $this->getData("person")->person_id;
                    $zustell_adresse->heimatadresse = false;
                    $zustell_adresse->zustelladresse = true;
                }
                $zustell_adresse->strasse = $post["zustell_strasse"];
                $zustell_adresse->plz = $post["zustell_plz"];
                $zustell_adresse->ort = $post["zustell_ort"];
                $zustell_adresse->nation = $post["zustelladresse_nation"];
                $updateZustellAdresse = $this->AdresseModel->saveZustellAdresse((array)$zustell_adresse);

                if(!isSuccess($updateZustellAdresse))
                {
                    $this->_setError(true, $updateZustellAdresse->retval);
                }
            }
        }
        if (($post["email"] != ""))
        {
            if (!(isset($this->getData("kontakt")["email"])))
            {
                $kontakt = new stdClass();
                $kontakt->person_id = $this->getData("person")->person_id;
                $kontakt->kontakttyp = "email";
                $kontakt->kontakt = $post["email"];
                $kontakt->zustellung = true;
            }
            else
            {
                $kontakt = $this->getData("kontakt")["email"];
                $kontakt->kontakt = $post["email"];
            }
            $updateKontakt = $this->KontaktModel->saveKontakt((array)$kontakt);

            if(!isSuccess($updateKontakt))
            {
                $this->_setError(true, $updateKontakt->retval);
            }
        }
        if ((isset($post["telefon"])) && ($post["telefon"] != ""))
        {
            if (!(isset($this->getData("kontakt")["telefon"])))
            {
                $kontakt = new stdClass();
                $kontakt->person_id = $this->getData("person")->person_id;
                $kontakt->kontakttyp = "telefon";
                $kontakt->kontakt = $post["telefon"];
            }
            else
            {
                $kontakt = $this->getData("kontakt")["telefon"];
                $kontakt->kontakt = $post["telefon"];
            }
            $updateKontakt = $this->KontaktModel->saveKontakt((array)$kontakt);

            if(!isSuccess($updateKontakt))
            {
                $this->_setError(true, $updateKontakt->retval);
            }
        }
        if ((isset($post["fax"])) && ($post["fax"] != ""))
        {
            if (!(isset($this->getData("kontakt")["fax"])))
            {
                $kontakt = new stdClass();
                $kontakt->person_id = $this->getData("person")->person_id;
                $kontakt->kontakttyp = "fax";
                $kontakt->kontakt = $post["fax"];
            }
            else
            {
                $kontakt = $this->getData("kontakt")["fax"];
                $kontakt->kontakt = $post["fax"];
            }
            $updateKontakt = $this->KontaktModel->saveKontakt((array)$kontakt);

            if(!isSuccess($updateKontakt))
            {
                $this->_setError(true, $updateKontakt->retval);
            }
        }

        $this->setData('person', $this->PersonModel->getPerson());

        $this->setRawData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId()->retval);

        $this->setData('adresse', $this->AdresseModel->getAdresse());

        $this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());

        foreach ($this->getData("gemeinden") as $gemeinde)
        {
            if (($this->getData("adresse") !== null) && ($gemeinde->plz == $this->getData("adresse")->plz) && ($gemeinde->name == $this->getData("adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("adresse")->ort))
            {
                $this->setRawData("ort_dd", $gemeinde->gemeinde_id);
            }
            if (($this->getData("zustell_adresse") !== null) && ($gemeinde->plz == $this->getData("zustell_adresse")->plz) && ($gemeinde->name == $this->getData("zustell_adresse")->gemeinde) && ($gemeinde->ortschaftsname == $this->getData("zustell_adresse")->ort))
            {
                $this->setRawData("zustell_ort_dd", $gemeinde->gemeinde_id);
            }
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

        //setting selected Studiengang by GET Param
        $abgeschickt_array = array();
        foreach ($this->getData('studiengaenge') as $stg)
        {
            if ($stg->studiengang_kz === $this->getData('studiengang_kz'))
            {
                $this->setRawData("studiengang", $stg);
            }

            if ($stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
            {
                $this->setRawData("bewerbung_abgeschickt", true);
                $abgeschickt_array[$stg->studiengang_kz] = true;
            }
        }
        $this->setRawData('abgeschickt_array', $abgeschickt_array);

        $this->setRawData("studiengaenge", array($this->getData('studiengang')));

        $this->setRawData('prestudent', $this->getData('studiengang')->prestudenten[0]);
        $this->setRawData('prestudentStatus', $this->getData('studiengang')->prestudentstatus[0]);

        if (($this->getData("error") === null) && ($this->getData("studiengang_kz") !== null) && ($this->getData('studienplan_id') !== null))
        {
            redirect("/Requirements?studiengang_kz=" . $this->getData("studiengang_kz") . "&studienplan_id=" . $this->getData('studienplan_id'));
            $this->setRawData("complete", $this->_checkDataCompleteness());
            $this->load->view('bewerbung', $this->getAllData());
        }
        else
        {
            $this->setRawData("complete", $this->_checkDataCompleteness());
            $this->load->view('bewerbung', $this->getAllData());
        }
    }
	
	/**
	 * 
	 */
	private function _missingData()
	{
		$person = $this->getData('person');
		$kontakt = $this->getData('kontakt');
		$adresse = $this->getData('adresse');
		$zustell_adresse = $this->getData('zustell_adresse');
		
		if ($person->anrede != null ||
			$person->titelpre != null ||
			$person->titelpost != null ||
			$person->gebort != null ||
			$person->staatsbuergerschaft != null ||
			$person->geburtsnation != null ||
			$person->svnr != null ||
			(!isset($kontakt['telefon']) || (isset($kontakt['telefon']) && $kontakt['telefon']->kontakt != null)) ||
			(!isset($adresse) ||
				$adresse->plz != null ||
				$adresse->strasse != null ||
				$adresse->ort != null ||
				$adresse->nation != null) ||
			(!isset($zustell_adresse) ||
				$zustell_adresse->plz != null ||
				$zustell_adresse->strasse != null ||
				$zustell_adresse->ort != null ||
				$zustell_adresse->nation != null)
		)
		{
			$this->setData('incomplete', success(true));
		}
		else
		{
			$this->setData('incomplete', success(false));
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
     *
     */
	private function _isAnyApplicationSent()
    {
        //check if any application is sent
        $abgeschickt_array = array();
        if($this->getData("prestudent") !== null)
        {
            foreach($this->getData("prestudent") as $prestudent)
            {
                if ((isset($prestudent->bewerbung_abgeschicktamum)) && ($prestudent->bewerbung_abgeschicktamum != null))
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                    $abgeschickt_array[$prestudent->studiengang_kz] = true;
                }
            }
        }
        $this->setRawData('abgeschickt_array', $abgeschickt_array);
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
                    if($p->phrase == $phrase)
                    {
                        if (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == $this->config->item("root_oe")) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            else
                                $text = $p->text;
                        }
                        elseif (($p->orgeinheit_kurzbz == null) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
                        {
                            if ($this->config->item('display_phrase_name'))
                                $text = $p->text . " <i>[$p->phrase]</i>";
                            else
                                $text = $p->text;
                        }
                    }
                }

                if($text != "")
                    return $text;

                if ($this->config->item('display_phrase_name'))
                    return "<i>[$phrase]</i>";
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