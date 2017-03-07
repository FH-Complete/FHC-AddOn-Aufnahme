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
		parent::__construct(Parent::NOT_CHECK_LOGIN);
		
		// 
		$this->load->library('form_validation');
		
		// Loading the 
		$this->load->model('person/Person_model', 'PersonModel');
        $this->load->model('person/Benutzer_model', 'BenutzerModel');
		
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
        $currentLanguage = $this->getCurrentLanguage();
        if (hasData($currentLanguage))
        {
            $this->setData('sprache', $currentLanguage);
        }
		
		if ($this->input->get('studiengang_kz') !== null)
		{
			$this->setRawData('studiengang_kz', $this->input->get('studiengang_kz'));
		}
		else
		{
			$this->setRawData('studiengang_kz', null);
		}

		$this->setRawData('username', $this->input->post('username'));
		$this->setRawData('email', $this->input->post('email'));
		$this->setRawData('password', $this->input->post('password'));
		$this->setRawData('code', $this->input->post('code') ? $this->input->post('code') : $this->input->get('code'));
		
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
				$this->setData('code_error_msg', 'Bitte geben Sie eine E-Mail Adresse und ein Passwort ein');
				$this->load->view('registration', $this->getAllData());
			}
		}
		else
		{
			if ($this->getData('code'))
			{
				$this->_codeLogin($this->getData('code'), $this->getData('email'));
				if ($this->getData('error_msg') !== null)
				{
					$this->load->view('registration', $this->getAllData());
				}
			}
			elseif ($this->getData('username') && $this->getData('password'))
			{
				$this->_cisLogin($this->getData('username'), $this->getData('password'));
				
				if ($this->getData('error_msg') !== null)
				{
					$this->load->view('registration', $this->getAllData());
				}
			}
			else
			{
				$this->load->view('login', $this->getAllData());
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
		$person = $this->PersonModel->getPerson($code, $email, REST_Model::AUTH_NOT_REQUIRED, true);
		if (hasData($person))
		{
			$this->setData('person', $person);
			$this->_redirect($this->getData('person'));
		}
		else
		{
			$this->setRawData('code_error_msg', 'E-Mail Adresse und/oder Passwort ist falsch.');
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
			$benutzer = $this->BenutzerModel->getBenutzer($username, REST_Model::AUTH_NOT_REQUIRED, true);

			if (hasData($benutzer))
			{
				$person = $this->PersonModel->getPersonByPersonId($benutzer->retval->person_id, true);

				if (hasData($person))
				{
					$this->setData('person', $person);
					$this->_redirect($this->getData('person'));
				}
				else
				{
					$this->setRawData('uid_error_msg', 'Person konnte nicht gefunden werden.');
				}
			}
			else
			{
				$this->setRawData('uid_error_msg', 'Benutzer existiert nicht.');
			}
		}
		else
		{
			$this->setRawData('uid_error_msg', 'Authentifizierung fehlgeschlagen.');
		}
	}
	
	/**
	 *
	 */
	private function _redirect($person)
	{
		$prestudent = null;
		$studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
		
		if (hasData($studiensemester))
		{
			$this->setData('studiensemester', $studiensemester);
			// Load last status of every prestudent
			$prestudent = $this->PrestudentModel->getLastStatuses(
				$person->person_id,
				$this->getData('studiensemester')->studiensemester_kurzbz,
				null,
				'Interessent'
			);
		}

		if (hasData($prestudent) || (isSuccess($prestudent) && empty($prestudent->retval)))
		{
			$this->setData('prestudent', $prestudent);
			if ($this->session->userdata('token') !== null)
			{
				redirect('/Registration?token='.$this->session->userdata('token'));
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
			$this->setRawData('uid_error_msg', 'Prestudent nicht gefunden.');
		}
	}
}