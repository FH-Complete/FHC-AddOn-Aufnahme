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
		
		$this->load->model('content/Dms_model', 'DmsModel');
		
		$this->load->model('codex/Gemeinde_model', 'GemeindeModel');
		$this->load->model('codex/Nation_model', 'NationModel');
		$this->load->model('codex/Bundesland_model', 'BundeslandModel');
		
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
		
		$studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
		if (hasData($studiensemester))
		{
			$this->setData('studiensemester', $studiensemester);
			$this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
				$this->getData('studiensemester')->studiensemester_kurzbz,
				'',
				'Interessent'
			));
		}
		
		$this->setData('prestudent', $this->PrestudentModel->getPrestudentByPersonId());
		
		$this->setData('kontakt', $this->KontaktModel->getOnlyKontaktByPersonId());
		
		$this->setData('adresse', $this->AdresseModel->getAdresse());
		
		$this->setData('zustell_adresse', $this->AdresseModel->getZustelladresse());
		
		$this->setData('nationen', $this->NationModel->getAll());
		
		$this->setData('bundeslaender', $this->BundeslandModel->getAll());
		
		$this->setData('', $this->GemeindeModel->getGemeinde());
		
		$this->setData('', $this->DmsModel->getAktenAcceptedDms());
		
		$this->_getPersonalDocuments();
		
		$this->_missingData();
		
		// Form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
		$this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
		$this->form_validation->set_rules("gebdatum", "Geburtsdatum", "callback_check_date");
		$this->form_validation->set_rules("email", "E-Mail", "required|valid_email");
		
		if ($this->form_validation->run() == FALSE)
		{
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
}