<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Start extends UI_Controller
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
		$this->lang->load(array('aufnahme', 'login'), $this->getCurrentLanguage());
		
		// 
		$this->load->helper("form");
		
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
		$this->setData('', $this->PhraseModel->getPhrasen(
			array('app' => 'aufnahme', 'sprache' => ucfirst($this->getCurrentLanguage())))
		);
		
		$this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
		
		$this->setData('', $this->StudiensemesterModel->getNextStudiensemester('WS'));
		
		$this->setData('person', $this->PersonModel->getPerson());
		
		$this->setData('studiengaenge', $this->StudiengangModel->getAppliedStudiengang(
			$studiensemester_kurzbz, $titel, $status_kurzbz
		));
		
		$this->setData('', $this->PrestudentModel->getPrestudentByPersonId());
		
		$this->setData('', $this->KontaktModel->getOnlyKontaktByPersonId());
		
		$this->setData('', $this->AdresseModel->getAdresse());
		
		$this->setData('', $this->NationModel->getAll());
		
		$this->setData('', $this->BundeslandModel->getAll());
		
		$this->setData('', $this->GemeindeModel->getGemeinde());
		
		$this->setData('', $this->DmsModel->getAktenAcceptedDms());
		
		/*$this->setData('', $this->DokumentModel->getDokument('reisepass'));
		
		$this->setData('', $this->DokumentModel->getDokument('lebenslauf'));*/
		
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
}