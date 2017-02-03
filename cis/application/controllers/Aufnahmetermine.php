<?php
/**
 * ./cis/application/controllers/Aufnahmetermine.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Aufnahmetermine extends UI_Controller
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
            $this->lang->load(array('termine'), $this->getData('sprache'));
        }

        $this->load->helper("form");

        // Loading the
        $this->load->model('system/Phrase_model', 'PhraseModel');

        $this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisation/Studiengang_model', 'StudiengangModel');
        $this->load->model('organisation/Studienplan_model', 'StudienplanModel');
        $this->load->model('organisation/Studiengangstyp_model', 'StudiengangstypModel');

        $this->load->model('person/Person_model', 'PersonModel');
        $this->load->model('crm/Reihungstest_model', "ReihungstestModel");

        $this->load->model('crm/Prestudent_model', 'PrestudentModel');
        $this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');

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

        //workaround for inserting code for Google Tag Manager
        if (isset($this->input->get()["send"]))
        {
            $time = time();
            if (!(($time - $this->input->get()["send"]) > 5))
            {
                $this->setRawData("gtm", true);
            }
        }

        $this->_loadData();

        $this->load->view('aufnahmetermine', $this->getAllData());
    }


    /**
     *
     * @param unknown $studiengang_kz
     * @param unknown $studienplan_id
     */
    public function register($studiengang_kz, $studienplan_id)
    {
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());

        $this->setData('person', $this->PersonModel->getPerson());

        $reihungstest = $this->ReihungstestModel->getReihungstest($this->input->post()["rtTermin"]);

        if(hasData($reihungstest))
        {
            $reihungstest = $reihungstest->retval;

            $this->_loadData();

            if (date("Y-m-d", strtotime($reihungstest->anmeldefrist)) > date("Y-m-d"))
            {
                $rtToInsert = new stdClass();
                $rtToInsert->new = true;
                $rtToInsert->person_id = $this->getData('person')->person_id;
                $rtToInsert->rt_id = $this->input->post()["rtTermin"];
                $rtToInsert->studienplan_id = $studienplan_id;
                $rtToInsert->anmeldedatum = date('Y-m-d');
                //check if new registration or change
                if (($this->getData("anmeldungen") !== null) && (!empty($this->getData("anmeldungen")[$studiengang_kz])))
                {
                    foreach ($this->getData("anmeldungen") as $key => $anmeldungen)
                    {
                        foreach ($anmeldungen as $anmeldung)
                        {
                            if (($anmeldung->studiengang_kz === $studiengang_kz) && ($anmeldung->studienplan_id === $studienplan_id) && ($anmeldung->reihungstest_id !== $this->input->post()["rtTermin"]))
                            {
                                $rtToDelete = new stdClass();
                                $rtToDelete->person_id = $anmeldung->person_id;
                                $rtToDelete->rt_person_id = $anmeldung->rt_person_id;
                                $rtToDelete->rt_id = $anmeldung->rt_id;

                                $deletedRt = $this->PrestudentModel->removeRegistrationToReihungstest((array)$rtToDelete);

                                if(isSuccess($deletedRt))
                                {
                                    $insertedRt = $this->PrestudentModel->registerToReihungstest((array)$rtToInsert);

                                    if(isSuccess($insertedRt))
                                    {
                                        foreach ($this->getData("studiengaenge") as $studiengang)
                                        {
                                            if ($studiengang->studiengang_kz === $studiengang_kz)
                                            {
                                                $studiengang->studiengangstyp = $this->StudiengangstypModel->getStudiengangstyp($studiengang->typ)->retval;
                                                $this->_sendMessageMailAppointmentConfirmation($this->getData("person"), $studiengang, $reihungstest);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $this->_setError(true, 'Could not enroll to appiontment');
                                    }
                                }
                                else
                                {
                                    $this->_setError(true, 'Could not delete previous appointment');
                                }
                            }
                            //register if there are existing registrations for other studienplan of same stg
                            elseif (($anmeldung->studiengang_kz === $studiengang_kz) && ($anmeldung->studienplan_id != $studienplan_id) && ($anmeldung->reihungstest_id !== $this->input->post()["rtTermin"]))
                            {
                                $insertedRt = $this->PrestudentModel->registerToReihungstest((array)$rtToInsert);

                                if(isSuccess($insertedRt))
                                {
                                    foreach ($this->getData("studiengaenge") as $studiengang)
                                    {
                                        if ($studiengang->studiengang_kz === $studiengang_kz)
                                        {
                                            $studiengang->studiengangstyp = $this->StudiengangstypModel->getStudiengangstyp($studiengang->typ)->retval;
                                            $this->_sendMessageMailAppointmentConfirmation($this->getData("person"), $studiengang, $reihungstest);
                                        }
                                    }
                                }
                                else
                                {
                                    $this->_setError(true, 'Could not enroll to appiontment');
                                }
                            }
                        }
                    }
                }
                else
                {
                    $insertedRt = $this->PrestudentModel->registerToReihungstest((array)$rtToInsert);

                    if(isSuccess($insertedRt))
                    {
                        foreach ($this->getData("studiengaenge") as $studiengang)
                        {
                            if ($studiengang->studiengang_kz === $studiengang_kz)
                            {
                                $studiengang->studiengangstyp = $this->StudiengangstypModel->getStudiengangstyp($studiengang->typ)->retval;
                                $this->_sendMessageMailAppointmentConfirmation($this->getData("person"), $studiengang, $reihungstest);
                            }
                        }
                    }
                    else
                    {
                        $this->_setError(true, 'Could not enroll to appiontment');
                    }
                }
                $this->_loadData();
            }
            else
            {
                $this->setRawData("anmeldeMessage", $this->getPhrase("Test/FristAbgelaufen", $this->getData("sprache"), $this->config->item('root_oe')));
            }
        }
        else
        {
            $this->_loadData();
        }
        $this->load->view('aufnahmetermine', $this->getAllData());
    }

    /**
     *
     */
    private function _loadData()
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
        
        //load preinteressent data
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

        $this->_loadAvailablesTests();
        
        $this->_loadRegisteredTests();
    }

    private function _sendMessageMailAppointmentConfirmation($person, $studiengang, $termin)
    {
        $data = array(
            "typ" => $studiengang->studiengangstyp->bezeichnung,
            "studiengang" => $studiengang->bezeichnung,
            "orgform" => $studiengang->orgform_kurzbz,
            "termin" => date("d.m.Y", strtotime($termin->datum)) . " " . date("H:i", strtotime($termin->uhrzeit)),
            "anrede" => $person->anrede,
            "vorname" => $person->vorname,
            "nachname" => $person->nachname,
            "stgMail" => $studiengang->email
        );

        $oe = $studiengang->oe_kurzbz;
        $orgform_kurzbz = $studiengang->orgform_kurzbz;

        (isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->getData("sprache");

        /*$messageArray = array(
            "vorlage_kurzbz" => 'MailAppointmentConfirmation',
            "oe_kurzbz" => $oe,
            "data" => $data,
            "sprache" => ucfirst($sprache),
            "orgform_kurzbz" => $orgform_kurzbz,
            "relationmessage_id" => null,
            "multiPartMime" => false,
            'receiver_id' => $person->person_id
        );*/

        $message = $this->MessageModel->sendMessageVorlage('MailAppointmentConfirmation', $oe, $data, $sprache, $orgform_kurzbz, null, true, $person->person_id);

        if(hasData($message))
        {
            //success
        }
        else
        {
            $this->setData("message", '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />');
            //TODO set error message
            //$this->_setError(true, 'Could not send message'." ".$message->fhcCode);
        }
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
	
    private function _loadAvailablesTests()
	{
		$reihungstestsStg = $this->ReihungstestModel->getAvailableReihungstestByPersonId();
		
		$reihungstests = array();
		
		if (hasData($reihungstestsStg))
		{
			foreach ($reihungstestsStg->retval as $stg)
			{
				$tmp = new stdClass();
				$tmp->reihungstest = array();
				
				if (is_array($stg->reihungstest))
				{
					foreach($stg->reihungstest as $reihungstest)
					{
						if ($reihungstest->stufe == null)
						{
							$reihungstest->stufe = 0;
						}
						
						if (isset($tmp->reihungstest[$reihungstest->stufe]) && !is_array($tmp->reihungstest[$reihungstest->stufe]))
						{
							$tmp->reihungstest[$reihungstest->stufe] = array();
						}
						
						$tmp->reihungstest[$reihungstest->stufe][$reihungstest->reihungstest_id] = date('d.m.Y', strtotime($reihungstest->datum));
					}
				}
				
				$reihungstests[$stg->studiengang_kz] = $tmp;
			}
		}
		
		$this->setRawData('reihungstests', $reihungstests);
	}
	
	private function _loadRegisteredTests()
	{
		$anmeldungen = $this->ReihungstestModel->getReihungstestByPersonID();
		$registeredReihungstests = array();
		
		if (hasData($anmeldungen))
		{
			foreach($anmeldungen->retval as $registeredReihungstest)
			{
				if (isset($registeredReihungstests[$registeredReihungstest->studiengang_kz])
					&& !is_array($registeredReihungstests[$registeredReihungstest->studiengang_kz]))
				{
					$registeredReihungstests[$registeredReihungstest->studiengang_kz] = array();
				}
				
				$stufe = 0;
				if ($registeredReihungstest->stufe != null)
				{
					$stufe = $registeredReihungstest->stufe;
				}
				
				$registeredReihungstests[$registeredReihungstest->studiengang_kz][$stufe] = $registeredReihungstest->reihungstest_id;
			}
		}
		
        $this->setRawData('registeredReihungstests', $registeredReihungstests);
	}
}