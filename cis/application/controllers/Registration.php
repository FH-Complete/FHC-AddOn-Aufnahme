<?php
/**
 * ./cis/application/controllers/Registration.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Registration extends UI_Controller
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
		parent::__construct(Parent::NOT_CHECK_LOGIN);
		
		// 
		$this->load->library('form_validation');
		$this->load->library('securimage/securimage');
		
		// 
		$this->load->helper('form');
		
		// 
		$currentLanguage = $this->getCurrentLanguage();
		if (hasData($currentLanguage))
		{
			$this->setData('sprache', $currentLanguage);
			$this->lang->load(array('aufnahme', 'login', 'registration'), $this->getData('sprache'));
		}
		
		$this->load->model('person/Adresse_model', 'AdresseModel');
		$this->load->model('person/Person_model', 'PersonModel');
		$this->load->model('system/Phrase_model', 'PhraseModel');
		$this->load->model('system/Message_model', 'MessageModel');
	}
	
	/**
	 *
	 */
	public function index()
    {
		$this->PhraseModel->getPhrasen(
			'aufnahme',
			ucfirst($this->getData('sprache')),
			REST_Model::AUTH_NOT_REQUIRED
		);
		
		if (isset($this->input->get()['sprache']))
		{
			$this->setCurrentLanguage($this->input->get()['sprache']);
			$this->setData('sprache', $this->input->get()['sprache']);
			$this->lang->load(array('aufnahme', 'login', 'registration'), $this->getData('sprache'));
		}

        if(isset($this->input->get()['token']))
        {
            $this->session->set_userdata('token', $this->input->get()['token']);

            $messageByToken = $this->MessageModel->getMessageByToken($this->input->get()['token']);
            if (hasData($messageByToken))
            {
				$this->setData('messages', $messageByToken);
				$messageId = $this->getData('messages')->message_id;
				$oe_kurzbz = $this->getData('messages')->oe_kurzbz;;
				redirect('/Messages/answerMessage/' . $messageId . '/' . $oe_kurzbz);
            }
        }

		$this->setRawData('studiengang_kz', $this->input->get('studiengang_kz'));
		
		//form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules('vorname', 'Vorname', 'required|max_length[32]');
		$this->form_validation->set_rules('nachname', 'Nachname', 'required|max_length[64]');
		$this->form_validation->set_rules('geb_datum', 'Geburtsdatum', 'required|callback_check_date');
		$this->form_validation->set_rules('email', 'E-Mail', 'required|valid_email');
		$this->form_validation->set_rules('email2', 'E-Mail', 'required|valid_email|callback_check_email');
		$this->form_validation->set_rules('datenschutz', 'Datenschutz', 'callback_check_terms');
		$this->form_validation->set_rules('captcha_code', 'Captcha', 'required|max_length[6]|callback_check_captcha');

		if ($this->form_validation->run() == false)
		{
			$this->load->view('registration', $this->getAllData());
		}
		else
		{
			error_log('adfasdf');
			
			$this->setRawData('vorname' ,$this->input->post('vorname'));
			$this->setRawData('nachname' ,$this->input->post('nachname'));
			$this->setRawData('geb_datum' ,$this->input->post('geb_datum'));
			$this->setRawData('email' ,$this->input->post('email'));
			$this->setRawData('captcha_code' ,$this->input->post('captcha_code'));
			$this->setRawData('zugangscode' ,$this->input->post('zugangscode'));
			$this->setRawData('geschlecht' ,$this->input->post('geschlecht'));
		
			$this->saveRegistration($this->getAllData());
		}
	}
	
	/**
	 *
	 * @param unknown $random (optional)
	 */
	public function securimage($random = null)
    {
		$img = new Securimage();
		$img->show(); // alternate use: $img->show('/path/to/background.jpg');
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function check_captcha()
    {
		if ($this->input->post('email') != $this->config->config['codeception_email'])
		{
			$securimage = new Securimage();
			if (!$securimage->check($this->input->post('captcha_code')))
			{
				$this->form_validation->set_rules('captcha_code', 'E-Captcha', 'check_captcha');
				return false;
			}
		}
		
		return true;
	}

	/**
	 *
	 * @return unknown
	 */
	public function check_email()
	{
		if (!($this->input->post('email') == $this->input->post('email2')))
		{
			$this->form_validation->set_rules('email2', 'E-Mail', 'check_email');
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 */
	public function check_terms()
	{
		if (($this->input->post('datenschutz') !== ''))
		{
			$this->form_validation->set_message('check_terms', 'Sie müssen die Datenschutzbedingungen akzeptieren.');
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function check_date()
	{
		$date = explode('.', $this->input->post('geb_datum'));
		if ((is_array($date)) && (count($date) == 3) && (!checkdate($date[1], $date[0], $date[2])))
		{
			$this->form_validation->set_message('check_date', 'Bitte geben Sie ein gültiges Datum an.');
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 */
	public function resendCode()
    {
		if (($this->input->post('email') != null) && ($this->input->post('email') != ''))
		{
			$this->setRawData('email', $this->input->post('email'));
			$bewerbung = $this->PersonModel->checkBewerbung($this->getData('email'));

			if (hasData($bewerbung))
			{
				$person = $this->PersonModel->getPersonByPersonId($person->person_id);

				$person->zugangscode_timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$this->config->item('invalidateResendTimestampAfter').' hour'));
				$person->zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
				$this->_savePerson($person);
				//$this->PersonModel->savePerson($person);

				$message = $this->resendMail($person->zugangscode,  $this->_data['email'], $person->person_id);
				$this->setRawData('message', $message);
			}
			else
			{
				$this->_setError(true, $this->lang->line('aufnahme/eMailAdresseNichtEindeutig'));
			}
		}
		elseif(isset($this->input->get()['email']))
		{
			$this->setRawData('email', $this->input->get()['email']);
		}
		else
		{
			if (!empty($this->input->post()))
			{
				$this->_setError(true, $this->lang->line('aufnahme/eMailAdresseFehlt'));
			}
		}

		$this->load->view('registration/resendCode', $this->_data);
	}
	
	public function confirm()
    {
		if (isset($this->input->get()['studiengang_kz']))
		{
			$this->setRawData('studiengang_kz', $this->input->get()['studiengang_kz']);
		}
		
		if(!isset($this->session->userdata()['zugangscode']))
		{
			$this->session->set_userdata('zugangscode', $this->input->get('code'));
		}
		
		if(isset($this->input->get()['email']))
		{
			$this->setRawData('email', $this->input->get()['email']);
		}
		else
		{
			if(isset($this->session->userdata()['zugangscode']))
			{
				$this->session->unset_userdata('zugangscode');
			}
			
			$this->setRawData('zugangscode', '');
			$this->setData(
				'message',
				'<span class="error">' . $this->lang->line('aufnahme/emailFehlt') . '</span>
				<br />
				<a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
			);
			$this->setRawData('email', '');
			$this->load->view('login/confirm_login', $this->getAllData());
		}
		
		$person = $this->PersonModel->getPerson($this->session->userdata()['zugangscode'], $this->getData('email'));
		$this->setData('person', $person);
		if (hasData($person))
		{
			//check if timestamp code is not older than now
			if (strtotime(date('Y-m-d H:i:s')) < strtotime($person->zugangscode_timestamp))
			{
				$this->setRawData('zugangscode', substr(md5(openssl_random_pseudo_bytes(20)), 0, 10));
				$this->session->set_userdata('zugangscode', $this->getData('zugangscode'));
				$person->zugangscode =  $this->getData('zugangscode');
				$this->PersonModel->updatePerson($person);
				$this->load->view('login/confirm_login',  $this->getAllData());
			}
			else
			{
				$this->setRawData(
					'message',
					'<span class="error">' . $this->lang->line('aufnahme/codeNichtMehrGueltig') . '</span>
					<br />
					<a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>')
				;
				$this->load->view('login/confirm_error',  $this->getAllData());
			}
		}
		else
		{
			if(isset($this->session->userdata()['zugangscode']))
			{
				$this->session->unset_userdata('zugangscode');
			}
			$this->setRawData('zugangscode', '');
			$this->setRawData(
				'message',
				'<span class="error">' . $this->lang->line('aufnahme/fehler') . '</span>
				<br />
				<a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
			);
			$this->setRawData('email', '');
			$this->load->view('login/confirm_login',  $this->getAllData());
		}
	}


	private function saveRegistration($data)
    {
		$zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
		$person = new stdClass();
		$person->vorname = $data['vorname'];
		$person->nachname = $data['nachname'];
		$person->gebdatum = date('Y-m-d', strtotime($data['geb_datum']));
		$person->zugangscode = $zugangscode;
		//set timestamp which is indicated how long the code is valid
		$person->zugangscode_timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +'.$this->config->item('invalidateRegistrationTimestampAfter').' hours'));
		$person->insertvon = 'online';
		$person->vornamen = '';
		$person->aktiv = true;
		$person->geschlecht = 'u';
		$person->sprache = ucfirst($this->getData('sprache'));

		$bewerbung = $this->PersonModel->checkBewerbung($this->getData('email'));
		if (isSuccess($bewerbung))
		{
			if (hasData($bewerbung))
			{
				$data['message'] = '<p class="alert alert-danger" id="danger-alert">' . sprintf($this->lang->line('aufnahme/mailadresseBereitsGenutzt'), $data['email']) . '</p>'
					. '<a href="' . base_url('index.dist.php/Registration/resendCode?email='.$this->_data['email']) . '"><button type="submit" class="btn btn-primary">' . $this->lang->line('aufnahme/codeZuschicken') . '</button></a>';
				$this->load->view('registration', $data);
			}
			else
			{
				$person = $this->_savePerson($person);
				if ($this->PersonModel->isResultValid() === true)
				{
					$kontakt = new stdClass();
					$kontakt->person_id = $person_id;
					$kontakt->kontakttyp = 'email';
					$kontakt->kontakt = $data['email'];
					$kontakt->insertamum = date('Y-m-d H:i:s');
					$kontakt->insertvon = 'online';
					$kontakt->zustellung = true;
					$this->Kontakt_model->saveKontakt($kontakt);

					//TODO error handling
					if ($this->Kontakt_model->isResultValid() === true) {
						//$message = $this->sendMail($zugangscode, $data['email'], $person_id, $data['studiengang_kz']);
						$this->_data['person'] = $this->_getPerson($person_id);

						if ($this->PersonModel->isResultValid() === true)
						{
							$this->_sendMessageVorlage($this->_data['person'], $zugangscode, base_url($this->config->config['index_page'].'/Registration/confirm?code='.$zugangscode.'&studiengang_kz='.$data['studiengang_kz']).'&email='.$data['email'], $data['email']);

							//$data['message'] = $message;
							//       $this->load->view('templates/header');
							$this->_data['success'] = true;
							$this->load->view('registration', $this->_data);
							//       $this->load->view('templates/footer');
						}
						else
						{
							//error message already setn
						}
					}
					else
					{
						$this->_setError(true, $this->Kontakt_model->getErrorMessage());
					}

					//      $adresse = new stdClass();
					//      $adresse->person_id =$person_id;
					//      $adresse->heimatadresse = true;
					//      $adresse->zustelladresse = false;
					////      $adresse->ort = $data['wohnort'];
					//
					//      $this->_saveAdresse($adresse);
				}
				else
				{
					//error message already set
					$this->_setError(true, $this->PersonModel->getErrorMessage());
				}
			}
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	public function code_login()
	{
        $this->_loadModels(array('PersonModel'=>'Person_model'));
		$studiengang_kz = $this->input->get()['studiengang_kz'];
		$code = $this->input->post('password');
		$email = $this->input->post('email');
		$this->PersonModel->getPersonFromCode($code, $email);

		if ($this->PersonModel->isResultValid() === true) {
			if((isset($this->PersonModel->result->retval)) && (count($this->PersonModel->result->retval) == 1))
			{
				$data['person'] = $this->PersonModel->result->retval[0];
				if (isset($data['person']->person_id))
				{
					$this->session->set_userdata('person_id', $data['person']->person_id);
					if((isset($studiengang_kz)) && ($studiengang_kz != ''))
					{
						redirect('/Studiengaenge/?studiengang_kz='.$studiengang_kz);
					}
					else
					{
						redirect('/Studiengaenge');
					}
				} 
				else
				{
					$data['wrong_code'] = true;
				}
			}
			else
			{
				$data['wrong_code'] = true;
			}
		}
		else {
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	private function sendMail($zugangscode, $email, $person_id = null, $studiengang_kz = '')
    {
		if ($person_id != '') {
			$this->PersonModel->getPersonen($person_id);
			if ($this->PersonModel->result->error == 0) {
				$person = $this->PersonModel->result->retval[0];
				$vorname = $person->vorname;
				$nachname = $person->nachname;
				$geschlecht = $person->geschlecht;
			} else {
				return $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
		}
		if ($geschlecht == 'm')
			$anrede = $this->lang->line('aufnahme/anredeMaennlich');
		elseif($geschlecht == 'w')
			$anrede = $this->lang->line('aufnahme/anredeWeiblich');
		else
			$anrede = $this->lang->line('aufnahme/anredeUnknown');

		$this->load->library('mail', array('to' => $email, 'from' => 'no-reply', 'subject' => $this->lang->line('aufnahme/registration'), 'text' => $this->lang->line('aufnahme/mailtextHtml')));
		$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, $studiengang_kz);
		$this->mail->setHTMLContent($text);
		if (!$this->mail->send())

			$msg = '<span class="error">' . $this->getPhrase('Registration/EmailAddressTaken', $this->_data['sprache'], $this->config->item('root_oe')) . '</span><br /><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
		else
			$msg = sprintf($this->getPhrase('Registration/EmailWithAccessCodeSent', $this->_data['sprache'], $this->config->item('root_oe')), $email) . '<br><br><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';

		return $msg;
	}


	private function resendMail($zugangscode, $email, $person_id = null)
    {
		if ($person_id != '') {
			$this->PersonModel->getPersonen($person_id);
			if ($this->PersonModel->result->error == 0) {
				$person = $this->PersonModel->result->retval[0];
				$vorname = $person->vorname;
				$nachname = $person->nachname;
				$geschlecht = $person->geschlecht;
			}
		}

		if ($geschlecht == 'm')
			$anrede = $this->lang->line('aufnahme/anredeMaennlich');
		elseif($geschlecht == 'w')
			$anrede = $this->lang->line('aufnahme/anredeWeiblich');
		else
			$anrede = $this->lang->line('aufnahme/anredeUnknown');

		$this->load->library('mail', array('to' => $email, 'from' => 'no-reply', 'subject' => $this->lang->line('aufnahme/registration'), 'text' => $this->lang->line('aufnahme/mailtextHtml')));
		$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, NULL, $email);
		$this->mail->setHTMLContent($text);
		if (!$this->mail->send())
			$msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
		else
			$msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . '<br><br><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';

		return $msg;
	}


	private function _sendMessageVorlage($person, $code, $link, $email)
	{
		$data = array(
			'anrede' => (is_null($person->anrede)) ? '' : $person->anrede,
			'vorname' => $person->vorname,
			'nachname' => $person->nachname,
			'code' => $code,
			'link' => $link,
			'eMailAdresse' => $email,
		);

		if ($this->config->item('root_oe'))
			$oe = $this->config->item('root_oe');
		else
			$oe = 'fhstp';

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->_data['sprache'];

		$this->MessageModel->sendMessageVorlage('MailRegistrationConfirmation', $oe, $data, $sprache, $orgform_kurzbz=null, null, $person->person_id, false);

		if($this->MessageModel->isResultValid() === true)
		{
			if((isset($this->MessageModel->result->error)) && ($this->MessageModel->result->error === 0))
			{
				$this->_data['message'] = sprintf($this->getPhrase('Registration/EmailWithAccessCodeSent', $this->_data['sprache'], $this->config->item('root_oe')), $email) . '<br><br><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
			else
			{
				$this->_data['message'] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
		}
		else
		{
			$this->_data['message'] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url('index.dist.php') . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}

	private function _checkZugangscodePerson($code)
	{
		$this->PersonModel->checkZugangscodePerson(array('code' => $code));
		if($this->PersonModel->isResultValid() === true)
		{
			return $this->PersonModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}
	
	private function _setError($bool, $msg = null)
	{
		$this->setRawData('error') = new stdClass();
		$this->setRawData('error')->error = $bool;
		$this->setRawData('error')->msg = $msg;
	}
}