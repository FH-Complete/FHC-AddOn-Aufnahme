<?php
/**
 * ./cis/application/controllers/Dokumente.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Helper extends Helper_Controller
{

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
    public function __construct($checkLogin = true)
    {

        parent::__construct();

    }

    /**
     *
     */
    public function index()
    {

    }

    public function areDocumentsComplete()
    {
        // Loading the
        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');

        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('person/Person_model', 'PersonModel');

        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');

        $this->load->model('content/Dms_model', 'DmsModel');


        //load preinteressent data
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

        $this->setData('person', $this->PersonModel->getPerson());

        if ($this->getData('person') !== null)
        {
            $result = new stdClass();
            $result->complete = true;
            //load dokumente
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

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

            if ($this->getData("prestudent") !== null)
            {
                if (($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
                {
                    $result->complete = false;
                }

                if (($this->getData('dokumente') !== null) && (!isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
                {
                    $result->complete = false;
                }

                if (!isset($this->input->post()["studiengang_kz"]))
                {
                    if ($this->getData('prestudent') !== null)
                    {
                        foreach ($this->getData("prestudent") as $prestudent)
                        {
                            if ($prestudent->status_kurzbz == "Interessent")
                            {
                                $doks = $this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($prestudent->studiengang_kz, true, null)->retval;
                                foreach ($doks as $dok)
                                {
                                    if (($this->getData('dokumente') !== null) && ((!isset($this->getData("dokumente")[$dok->dokument_kurzbz])) && ($dok->pflicht == true)))
                                    {
                                        $result->complete = false;
                                    }
                                }

                                if ($prestudent->bewerbung_abgeschicktamum !== null)
                                {
                                    $result->abgeschickt = true;
                                }
                                else
                                {
                                    $result->abgeschickt = false;
                                }
                            }
                        }
                    }
                }
                else
                {
                    if ($this->getData('prestudent') !== null)
                    {
                        foreach ($this->getData("prestudent") as $prestudent)
                        {
                            if ($prestudent->studiengang_kz === $this->input->post("studiengang_kz"))
                            {
                                if ($prestudent->status_kurzbz == "Interessent")
                                {
                                    $doks = $this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($prestudent->studiengang_kz, true, null)->retval;
                                    foreach ($doks as $dok)
                                    {
                                        if (($this->getData('dokumente') !== null) && ((!isset($this->getData("dokumente")[$dok->dokument_kurzbz])) && ($dok->pflicht == true)))
                                        {
                                            $result->complete = false;
                                        }
                                    }

                                    if ($prestudent->bewerbung_abgeschicktamum !== null)
                                    {
                                        $result->abgeschickt = true;
                                    }
                                    else
                                    {
                                        $result->abgeschickt = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                $result->complete = true;
            }
            echo json_encode($result);
        }
    }

    public function checkDataCompleteness()
    {
        // Loading the
        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');

        $this->load->model('person/Adresse_model', 'AdresseModel');
        $this->load->model('person/Kontakt_model', 'KontaktModel');
        $this->load->model('person/Person_model', 'PersonModel');

        $this->load->model('crm/Akte_model', 'AkteModel');
        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');

        $this->load->model('content/Dms_model', 'DmsModel');

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
    }

    function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null)
    {
        $this->load->model('system/Phrase_model', 'PhraseModel');

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
    /**
     *
     */
    private function _getPersonalDocuments()
    {
        $this->load->model('crm/Dokument_model', 'DokumentModel');

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

    public function getOption()
    {
        $this->load->model('person/Person_model', "PersonModel");
        $this->load->model('crm/Akte_model', 'AkteModel');

        if(isset($this->input->post()["studiengangtyp"]))
        {
            $this->setData('person', $this->PersonModel->getPerson());
            if ($this->getData("person") !== null)
            {
                $result = new stdClass();
                $this->setRawData('dokumente' , $this->AkteModel->getAktenAccepted()->retval);

                if ((isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]])) && ($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]]->anmerkung != null))
                {
                    $result->error = 0;
                    $result->result = $this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$this->input->post()["studiengangtyp"]]]->anmerkung;
                }
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
