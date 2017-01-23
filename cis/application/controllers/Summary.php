<?php
/**
 * ./cis/application/controllers/Summary.php
 *
 * @package default
 */


class Summary extends UI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$currentLanguage = $this->getCurrentLanguage();
		if (hasData($currentLanguage))
		{
			$this->setData('sprache', $currentLanguage);
			$this->lang->load(array('summary'), $this->getData('sprache'));
		}
		
		$this->load->model('system/Phrase_model', 'PhraseModel');
		$this->load->model('system/Message_model', 'MessageModel');
		
		$this->load->model('codex/Nation_model', 'NationModel');
		$this->load->model('codex/Bundesland_model', 'BundeslandModel');
		
		$this->load->model('content/Dms_model', 'DmsModel');
		
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('organisation/Studienplan_model', 'StudienplanModel');
		
		$this->load->model('person/Person_model', 'PersonModel');
		$this->load->model('person/Adresse_model', 'AdresseModel');
		$this->load->model('person/Kontakt_model', 'KontaktModel');
		
		$this->load->model('crm/Akte_model', 'AkteModel');
		$this->load->model('crm/Dokument_model', 'DokumentModel');
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');
        $this->load->model('crm/DokumentStudiengang_model', 'DokumentStudiengangModel');
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
		
		if ($this->input->get('studiengang_kz') != null)
        {
            $this->setRawData('studiengang_kz', $this->input->get('studiengang_kz'));
            $dokumenteStudiengang = array();
			$dokumenteStudiengang[$this->getData('studiengang_kz')] = $this->DokumentStudiengangModel->getDokumentStudiengangByStudiengang_kz($this->getData('studiengang_kz'), true, true)->retval;

			$this->setRawData('dokumenteStudiengang', $dokumenteStudiengang);
        }
        
        $this->setData('person', $this->PersonModel->getPerson());
        $person = $this->getData('person');
        
        $bundesland = $this->BundeslandModel->getBundesland($person->bundesland_code);
        if ($bundesland !== null)
        {
			$person->bundesland_bezeichnung = $bundesland->bezeichnung;
		}
		else
		{
			$person->bundesland_bezeichnung = null;
		}
        
        $nation = $this->NationModel->getNation($person->geburtsnation);
        if ($nation !== null)
        {
			$person->geburtsnation_text = $nation->kurztext;
		}
		else
		{
			$person->geburtsnation_text = null;
		}
		
        
        $this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
		
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

		if($this->getData('prestudent') !== null)
        {
            foreach($this->getData('prestudent') as $prestudent)
            {
                if($prestudent->bewerbung_abgeschicktamum !== null)
                {
                    $this->setRawData("bewerbung_abgeschickt", true);
                }
            }
        }
		
		//load bundeslaender
		$this->setData('bundeslaender', $this->BundeslandModel->getAll());
		
		//load nationen
		$this->setData('nationen', $this->NationModel->getAll());
		
		//load adresse
		$this->setData('adresse', $this->AdresseModel->getAdresse());
		
		//load kontakt
		$this->setData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId());
		
		//load dokumente
		$this->setData('dokumente', $this->DmsModel->getAktenAcceptedDms());
		
		$this->_getPersonalDocuments();
		
		$this->load->view('summary', $this->getAllData());
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
}