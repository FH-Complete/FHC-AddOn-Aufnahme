<?php
/**
 * ./cis/application/controllers/Dokumente.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Dokumente extends UI_Controller
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
    public function __construct()
    {
        parent::__construct();

        $currentLanguage = $this->getCurrentLanguage();
        if (hasData($currentLanguage))
        {
            $this->setData('sprache', $currentLanguage);
            $this->lang->load(array('dokumente'), $this->getData('sprache'));
        }

        $this->load->helper("form");

        // Loading the
        $this->load->model('system/Phrase_model', 'PhraseModel');

        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');
        $this->load->model('organisation/Studienplan_model', 'StudienplanModel');

        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('person/Person_model', 'PersonModel');

        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');
        $this->load->model('crm/Akte_model', 'AkteModel');
        $this->load->model('crm/Dokument_model', 'DokumentModel');

        $this->load->model('content/Dms_model', 'DmsModel');

        $this->load->model('system/Message_model', 'MessageModel');
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

        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

        $this->_loadData();

        $this->load->view('dokumente', $this->getAllData());
    }

    /**
     *
     */
    private function _loadData()
    {
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

        //load dokumente
        $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

        if ($this->getData("studiengaenge") !== null)
        {
            foreach ($this->getData("studiengaenge") as $stg)
            {
                $stg->dokumente = $this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($stg->studiengang_kz, true, null)->retval;
            }
        }

        $docs = array();
        if ($this->getData("studiengaenge") !== null)
        {
            foreach ($this->getData("studiengaenge") as $stg)
            {
                foreach ($stg->dokumente as $dok)
                {
                    if (!isset($docs[$dok->dokument_kurzbz]) || ($docs[$dok->dokument_kurzbz] == null))
                    {
                        $docs[$dok->dokument_kurzbz] = $dok;
                        $docs[$dok->dokument_kurzbz]->studiengaenge = array();
                    }

                    if (($this->getData("dokumente") !== null) && (isset($this->getData("dokumente")[$dok->dokument_kurzbz])))
                    {
                        $docs[$dok->dokument_kurzbz]->dokument = $this->getData("dokumente")[$dok->dokument_kurzbz];
                    }
                    $docs[$dok->dokument_kurzbz]->studiengaenge[$stg->studiengang_kz] = $stg;
                }

                if ((!isset($docs[$this->config->config["dokumentTypen"]["reisepass"]])) || ($docs[$this->config->config["dokumentTypen"]["reisepass"]] == null))
                {
                    $docs[$this->config->config["dokumentTypen"]["reisepass"]] = $this->DokumentModel->getDokument($this->config->config["dokumentTypen"]["reisepass"])->retval;
                    $docs[$this->config->config["dokumentTypen"]["reisepass"]]->studiengaenge = array();
                }

                if (($this->getData("dokumente") !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]])))
                {
                    $docs[$this->config->config["dokumentTypen"]["reisepass"]]->dokument = $this->getData("dokumente")[$this->config->config["dokumentTypen"]["reisepass"]];
                }
                $docs[$this->config->config["dokumentTypen"]["reisepass"]]->studiengaenge[$stg->studiengang_kz] = $stg;

                if ((!isset($docs[$this->config->config["dokumentTypen"]["lebenslauf"]])) || ($docs[$this->config->config["dokumentTypen"]["lebenslauf"]] == null))
                {
                    $docs[$this->config->config["dokumentTypen"]["lebenslauf"]] = $this->DokumentModel->getDokument($this->config->config["dokumentTypen"]["lebenslauf"])->retval;
                    $docs[$this->config->config["dokumentTypen"]["lebenslauf"]]->studiengaenge = array();
                }

                if (($this->getData("dokumente") !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]])))
                {
                    $docs[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument = $this->getData("dokumente")[$this->config->config["dokumentTypen"]["lebenslauf"]];
                }
                $docs[$this->config->config["dokumentTypen"]["lebenslauf"]]->studiengaenge[$stg->studiengang_kz] = $stg;

                if ((!isset($docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]])) || ($docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]] == null))
                {
                    $docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]] = $this->DokumentModel->getDokument($this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ])->retval;
                    $docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]]->studiengaenge = array();
                }

                if (($this->getData("dokumente") !== null) && (isset($this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]])))
                {
                    $docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]]->dokument = $this->getData("dokumente")[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]];
                }
                $docs[$this->config->config["dokumentTypen"]["abschlusszeugnis_" . $stg->typ]]->studiengaenge[$stg->studiengang_kz] = $stg;
            }
        }
        $this->setRawData("docs", $docs);
    }

    /**
     *
     * @return unknown
     */
    public function deleteDocument()
    {
        $result = new stdClass();
        if ((isset($this->input->post()["dms_id"])))
        {
            $dms_id = $this->input->post()["dms_id"];
            $this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());

            if ($this->getData('dokumente') !== null)
            {
                foreach ($this->getData("dokumente") as $dok)
                {
                    if (($dok->dms_id === $dms_id) && ($dok->accepted == false))
                    {
                        $result = $this->DmsModel->deleteDms($dms_id);
                        $result->dokument_kurzbz = $dok->dokument_kurzbz;
                    }
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

    /**
     * @param $typ
     */
    public function uploadFiles($typ)
    {
        $files = $_FILES;

        if (count($files) > 0)
        {
            //load person data
            $this->setData('person', $this->PersonModel->getPerson());

            $this->setData('prestudent', $this->PrestudentModel->getPrestudentByPersonId());

            //load dokumente
            $this->setRawData('dokumente', $this->AkteModel->getAktenAccepted()->retval);

            foreach ($this->getData("dokumente") as $akte)
            {
                if ($akte->dms_id != null)
                {
                    $dms = $this->DmsModel->getDms($akte->dms_id)->retval;
                    $akte->dokument = $dms;
                }
            }

            foreach ($files as $key => $file)
            {
                if (is_uploaded_file($file["tmp_name"][0]))
                {
                    $obj = array();
                    $obj['new'] = true;
                    $akte = new stdClass();

                    $obj['version'] = 0;
                    $obj['mimetype'] = $file["type"][0];
                    $obj['name'] = $file["name"][0];
                    $obj['oe_kurzbz'] = null;
                    //$obj['dokument_kurzbz'] = $key;

                    if ($typ)
                    {
                        $obj['dokument_kurzbz'] = $typ;
                    }

                    foreach ($this->getData("dokumente") as $akte_temp)
                    {
                        if (($akte_temp->dokument_kurzbz == $obj['dokument_kurzbz']) && ($obj['dokument_kurzbz'] != $this->config->item('dokumentTypen')["sonstiges"]))
                        {
                            //       $dms = $this->_loadDms($akte_temp->dms_id);
                            //       $obj['version = $dms->version+1;
                            $akte = $akte_temp;
                            $akte->updateamum = date("Y-m-d H:i:s");
                            $akte->updatevon = "online";

                            if ($akte->dms_id != null && !is_null($akte->dokument))
                            {
                                $obj = (array)$akte->dokument;
                                $obj['new'] = true;
                                $obj['version'] = ($obj['version'] + 1);

                                //    $obj['version'] = ($akte->dokument->version+1);
                                $obj['mimetype'] = $file["type"][0];
                                $obj['name'] = $file["name"][0];
                            }
                        }
                    }

                    $obj['kategorie_kurzbz'] = "Akte";

                    $type = pathinfo($file["name"][0], PATHINFO_EXTENSION);
                    $data = file_get_contents($file["tmp_name"][0]);
                    $obj['file_content'] = base64_encode($data);

                    $result = new stdClass();
                    $insertResult = $this->DmsModel->saveDms($obj);
                    if (isSuccess($insertResult))
                    {
                        if ($obj['version'] >= 0)
                        {
                            $akte->dms_id = $insertResult->retval->dms_id;
                            $result->dms_id = $akte->dms_id;
                            $akte->person_id = $this->getData("person")->person_id;
                            $akte->mimetype = $file["type"][0];

                            $akte->bezeichnung = mb_substr($obj['name'], 0, 32);
                            $akte->dokument_kurzbz = $obj['dokument_kurzbz'];
                            $akte->titel = $key;
                            $akte->insertvon = 'online';
                            $akte->nachgereicht = 'f';

                            unset($akte->uid);
                            unset($akte->inhalt_vorhanden);
                            $akte->dokument = null;
                            unset($akte->dokument);
                            unset($akte->nachgereicht_am);

                            $akte = (array)$akte;
                            $akteInsertResult = $this->AkteModel->saveAkte($akte);

                            if (isSuccess($akteInsertResult))
                            {
                                $result->success = true;
                                $result->akte_id = $akteInsertResult->retval;
                                $result->bezeichnung = $obj['name'];
                                $result->mimetype = $akte['mimetype'];
                            }
                            else
                            {
                                $result->success = false;
                            }
                        }
                        else
                        {
                            $akte->mimetype = $file["type"][0];
                            $akte->bezeichnung = mb_substr($obj['name'], 0, 32);
                            $akte->dokument_kurzbz = $obj['dokument_kurzbz'];
                            $akte->titel = $key;

                            unset($akte->uid);
                            unset($akte->inhalt_vorhanden);
                            $akte->dokument = null;
                            unset($akte->dokument);
                            unset($akte->nachgereicht_am);

                            $akte = (array)$akte;
                            $akteInsertResult = $this->AkteModel->saveAkte($akte);

                            if (isSuccess($akteInsertResult))
                            {
                                $result->success = true;

                            }
                            else
                            {
                                $result->success = false;
                            }
                        }

                        if ($typ == $this->config->item('dokumentTypen')["letztGueltigesZeugnis"])
                        {
                            $akte = new stdClass();

                            $this->setData('studiengang', $this->StudiengangModel->getStudiengang($this->input->post()["studiengang_kz"]));

                            foreach ($this->getData("dokumente") as $akte_temp)
                            {
                                if (($akte_temp->dokument_kurzbz == $this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ]))
                                {
                                    $akte = $akte_temp;
                                }
                            }

                            $akte->person_id = $this->getData("person")->person_id;
                            $akte->dokument_kurzbz = $this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ];
                            $akte->insertvon = 'online';
                            $akte->nachgereicht = true;
                            if (isset($this->input->post()["doktype"]))
                            {
                                $akte->anmerkung = $this->input->post("doktype");
                            }

                            foreach ($this->getData("prestudent") as $prestudent)
                            {
                                if ($prestudent->studiengang_kz == $this->input->post()["studiengang_kz"])
                                {
//									if(($prestudent->zgvdatum == null) && ($prestudent->zgvort == null))
                                    {
                                        $prestudent->zgvdatum = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis_" . $this->getData('studiengang')->typ] . "_nachreichenDatum_" . $this->input->post("studienplan_id"))));
                                        $prestudent->zgvort = "geplanter Abschluss";
                                        $prestudent = (array)$prestudent;
                                        $updatePrestudent = $this->PrestudentModel->savePrestudent($prestudent);
                                        if (!isSuccess($updatePrestudent))
                                        {
                                            $this->_setError(true, "could not save data");
                                        }
                                    }
                                }
                            }
                            //TODO set geplanter Abschluss
                            //$akte->geplanterAbschluss = date("Y-m-d", strtotime($this->input->post($this->config->item('dokumentTypen')["abschlusszeugnis"]."_nachreichenDatum_".$this->input->post("studienplan_id"))));

                            $akte = (array)$akte;
                            $updateAkte = $this->AkteModel->saveAkte($akte);
                            if (!isSuccess($updateAkte))
                            {
                                $this->_setError(true, "could not save document");
                            }
                        }

                        echo json_encode($result);
                    }
                    else
                    {
                        //TODO handle error
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

    public function areDocumentsComplete()
    {
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
                                    $doks = $this->DokumentStudiengangModel->$this->DokumentStudiengangModel->getDokumentstudiengangByStudiengang_kz($prestudent->studiengang_kz, true, null)->retval;
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
}
