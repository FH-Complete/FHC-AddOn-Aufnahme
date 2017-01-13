<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Login extends UI_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$this->load->library('form_validation');
		
		// Loading the 
		$this->load->model('person/Person_model', 'PersonModel');
		
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('crm/Prestudentstatus_model', 'PrestudentStatusModel');
		
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('organisation/Studienplan_model', 'StudienplanModel');
		
		$this->load->model('CheckUserAuth_model', 'CheckUserAuthModel');
	}
	
	/**
	 * 
	 */
	public function index()
	{
		$this->setData('sprache', success($this->getCurrentLanguage()));
		
		if (isset($this->input->get('studiengang_kz')))
		{
			$this->setData('studiengang_kz', $this->input->get('studiengang_kz'));
		}
		
		$this->setData('username', $this->input->get('username'));
		$this->setData('email', $this->input->get('email'));
		$this->setData('password', $this->input->get('password'));
		$this->setData('code', $this->input->post('code') ? $this->input->post('code') : $this->input->get('code'));
		
		if ($this->config->item('hybrid_login'))
		{
			if ($this->getData('email') && $this->getData('code'))
			{
				$this->_cisLogin($this->getData('email'), $this->getData('code'));
				$this->_codeLogin($this->getData('code'), $this->getData('email'));
				$this->load->view('registration', $this->getAllData());
			}
			else
			{
				$this->_data['code_error_msg'] = 'Bitte geben Sie eine E-Mail Adresse und ein Passwort ein';
				$this->load->view('registration', $this->_data);
			}
		}
		else
		{
			if ($this->_data['code'])
			{
				$this->_codeLogin($this->_data['code'], $this->_data, $this->_data['email']);
				if (isset($this->_data['error_msg']))
				{
					$this->load->view('registration', $this->_data);
				}
			}
			elseif ($this->_data['username'] && $this->_data['password'])
			{
				$this->_cisLogin($this->_data['username'], $this->_data['password']);
				
				if (isset($this->_data['error_msg']))
				{
					$this->load->view('registration', $this->_data);
				}
			}
			else
			{
				$this->load->view('login', $this->_data);
			}
		}
	}
	
	/**
	 *
	 * @param unknown $code
	 * @param unknown $data  (reference)
	 * @param unknown $email (optional)
	 */
	private function _codeLogin($code, $email = null)
	{
		$this->StudiensemesterModel->getNextStudiensemester('WS');
		
		$person = $this->PersonModel->getPerson($code, $email);
		if (hasData($person))
		{
			$this->setData('person', $person);
			$this->_redirect($person);
		}
		else
		{
			$this->setData('code_error_msg', 'E-Mail Adresse und/oder Passwort ist falsch.');
		}
	}
	
	/**
	 * 
	 */
	private function _cisLogin($username, $password)
	{
		$checkUserAuth = $this->CheckUserAuthModel->checkByUsernamePassword($username, $password);
		
		if ($checkUserAuth)
		{
			$benutzer = $this->BenutzerModel->getBenutzer($username);
			if (hasData($benutzer))
			{
				$this->setData('studiensemester_kurzbz', $this->StudiensemesterModel->getNextStudiensemester('WS'));

				$person = $this->PersonModel->getPerson($benutzer->person_id);
				if (hasData($person))
				{
					$this->setData('person', $person);
					$this->_redirect($person);
				}
				else
				{
					$this->setData('uid_error_msg', 'Person konnte nicht gefunden werden.');
				}
			}
			else
			{
				$this->setData('uid_error_msg', 'Benutzer existiert nicht.');
			}
		}
		else
		{
			$this->setData('uid_error_msg', 'Authentifizierung fehlgeschlagen.');
		}
	}
	
	/**
	 *
	 */
	private function _redirect($person)
	{
		// Load last status of every prestudent
		$prestudent = $this->PrestudentModel->getLastStatuses(
			$person->person_id,
			$this->getData('studiensemester_kurzbz'),
			null,
			'Interessent'
		);
		
		if (hasData($prestudent))
		{
			$this->setData('prestudent', $prestudent);
			
			if (isset($this->input->get('token')))
			{
				redirect('/Registration?token='.$this->input->get('token'));
			}

			if ($this->getData('studiengang_kz') != null)
			{
				redirect('/Bewerbung/'.$this->getData('studiengang_kz'));
			}
			else if (hasData($prestudent))
			{
				redirect('/Bewerbung');
			}
			else
			{
				redirect('/Studiengaenge');
			}
		}
		else
		{
			$this->setData('uid_error_msg', 'Prestudent nicht gefunden.');
		}
	}
}