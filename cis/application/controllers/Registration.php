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
		$this->load->model('person/Kontakt_model', 'KontaktModel');
		$this->load->model('system/Phrase_model', 'PhraseModel');
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
		if (isset($this->input->get()['language']))
		{
			$this->setCurrentLanguage(strtolower($this->input->get()['language']));
			$this->setRawData('sprache', strtolower($this->input->get()['language']));
			$this->lang->load(array('aufnahme', 'login', 'registration'), $this->getData('sprache'));
		}

        if (isset($this->input->get()['token']))
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
			$bewerbung = $this->PersonModel->checkBewerbung($this->getData('email'), null, true);

			if (hasData($bewerbung))
			{
				$person = $this->PersonModel->getPersonByPersonId($bewerbung->retval->person_id);

				if(hasData($person))
                {
                    $person = $person->retval;
                    $person->zugangscode_timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +' . $this->config->item('invalidateResendTimestampAfter') . ' hour'));
                    $person->zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
                    $this->PersonModel->savePerson((array)$person);

                    $this->resendMail($person->zugangscode, $this->getData('email'), $person);
                }
                else
                {
                    $this->_setError(true, "Person nicht gefunden.");
                }
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

		$this->load->view('registration/resendCode', $this->getAllData());
	}
	
	public function confirm()
    {
        $this->session->sess_destroy();
		if (isset($this->input->get()['studiengang_kz']))
		{
			$this->setRawData('studiengang_kz', $this->input->get()['studiengang_kz']);
		}

		if(isset($this->input->get()['initial']))
        {
            $this->setRawData('initial', true);
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
				<a href=' . base_url($this->config->config['index_page']) . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
			);
			$this->setRawData('email', '');
			$this->load->view('login/confirm_login', $this->getAllData());
		}
		
		$person = $this->PersonModel->getPerson($this->session->userdata()['zugangscode'], $this->getData('email'),true);
		$this->setData('person', $person);

		if (hasData($person))
		{
			//check if timestamp code is not older than now
			if (strtotime(date('Y-m-d H:i:s')) < strtotime($person->retval->zugangscode_timestamp))
			{
			    $person = $person->retval;
				$this->setRawData('zugangscode', substr(md5(openssl_random_pseudo_bytes(20)), 0, 10));
				$this->session->set_userdata('zugangscode', $this->getData('zugangscode'));
				$person->zugangscode =  $this->getData('zugangscode');
				$this->PersonModel->savePerson((array)$person);
				$this->load->view('login/confirm_login',  $this->getAllData());
			}
			else
			{
				$this->setRawData(
					'message',
					'<span class="error">' . $this->lang->line('aufnahme/codeNichtMehrGueltig') . '</span>
					<br />
					<a href=' . base_url($this->config->config['index_page']) . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>')
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
				<a href=' . base_url($this->config->config['index_page']) . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
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
					. '<a href="' . base_url($this->config->config['index_page'].'/Registration/resendCode?email='.$this->getData('email')) . '"><button type="submit" class="btn btn-primary">' . $this->lang->line('aufnahme/codeZuschicken') . '</button></a>';
				$this->load->view('registration', $data);
			}
			else
			{
				$savePerson = $this->PersonModel->savePerson((array)$person, REST_Model::AUTH_NOT_REQUIRED);

				if (hasData($savePerson))
				{
					$person_id = $savePerson->retval->person_id;
					$kontakt = new stdClass();
					$kontakt->person_id = $person_id;
					$kontakt->kontakttyp = 'email';
					$kontakt->kontakt = $data['email'];
					$kontakt->insertamum = date('Y-m-d H:i:s');
					$kontakt->insertvon = 'online';
					$kontakt->zustellung = true;
					$kontakt = $this->KontaktModel->saveKontakt((array)$kontakt, REST_Model::AUTH_NOT_REQUIRED);

					if (hasData($kontakt))
					{
						//$message = $this->sendMail($zugangscode, $data['email'], $person_id, $data['studiengang_kz']);
						// TODO Why is loaded???
						$person = $this->PersonModel->getPersonByPersonId($person_id);
						$this->setData('person', $person);
						if (hasData($person))
						{
							$this->_sendMessageVorlage(
								$this->getData('person'),
								$zugangscode,
								base_url($this->config->config['index_page'].'/Registration/confirm?code='.$zugangscode.'&studiengang_kz='.$data['studiengang_kz']).'&email='.$data['email'].'&initial',
								$data['email']
							);
							
							$this->setRawData('success', true);
							$this->load->view('registration', $this->getAllData());
						}
						else
						{
                            $this->_setError(true, $person->error . ' ' . $person->fhcCode);
						}
					}
					else
					{
						$this->_setError(true, $kontakt->error . ' ' . $kontakt->fhcCode);
					}
				}
				else
				{
					//Error message already set
					$this->_setError(true, $savePerson->error . ' ' . $savePerson->fhcCode);
				}
			}
		}
		else
		{
			$this->_setError(true, $bewerbung->error . ' ' . $bewerbung->fhcCode);
            $this->load->view('registration', $this->getAllData());
		}

		//needed to unset session after registration; otherwhise user is automatically logged in
        $this->session->sess_destroy();
	}


	public function code_login()
	{
		$studiengang_kz = $this->input->get()['studiengang_kz'];
		$code = $this->input->post('password');
		$email = $this->input->post('email');
		$person = $this->PersonModel->getPerson($code, $email, true);

		if (isSuccess($person))
		{
			if(hasData($person))
			{
				$this->setData('person', $person);
				if (isset($this->getData('person')->person_id))
				{
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
		else
		{
			$this->_setError(true, $person->error . ' ' . $person->fhcCode);
		}

		redirect("/Registration/confirm?code=&email=".$email."&studiengang_kz=".$studiengang_kz);
	}
	
	private function resendMail($zugangscode, $email, $person)
    {

        $link = site_url('/Registration/confirm?code='.$zugangscode.'&studiengang_kz=&email='.$email);

        $data = array(
            'anrede' => (is_null($person->anrede)) ? '' : $person->anrede,
            'vorname' => $person->vorname,
            'nachname' => $person->nachname,
            'link' => $link,
            'eMailAdresse' => $email
        );

        if ($this->config->item('root_oe'))
            $oe = $this->config->item('root_oe');
        else
            $oe = 'fhstp';

        (isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->getData('sprache');

        $message = $this->MessageModel->sendMessageVorlage('MailRegistrationResend', $oe, $data, $sprache, null, null, false, $person->person_id);
        
        if (hasData($message))
        {
            $this->setRawData(
                'message',
                sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . '<br><br><a href=' . base_url($this->config->config['index_page']) . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
            );
        }
        else
        {
            $this->setRawData(
                'message',
                '<div class=\'alert alert-success\'>'.
                '<span class="error">'
                . $this->lang->line('aufnahme/fehlerBeimSenden')
                . '</span>
                <br />
                <a href=' . base_url($this->config->config['index_page']) . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>'
            );
            $this->_setError(true, $message->error . ' ' . $message->fhcCode);
        }
	}


	private function _sendMessageVorlage($person, $code, $link, $email)
	{
		$data = array(
			'anrede' => (is_null($person->anrede)) ? '' : $person->anrede,
			'vorname' => $person->vorname,
			'nachname' => $person->nachname,
			'code' => $code,
			'link' => $link,
			'eMailAdresse' => $email
		);

		if ($this->config->item('root_oe'))
			$oe = $this->config->item('root_oe');
		else
			$oe = 'fhstp';

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->getData('sprache');

        /*$messageArray = array(
            "vorlage_kurzbz" => 'MailRegistrationConfirmation',
            "oe_kurzbz" => $oe,
            "data" => $data,
            "sprache" => ucfirst($sprache),
            "multiPartMime" => false
        );*/
		
		$message = $this->MessageModel->sendMessageVorlage('MailRegistrationConfirmation', $oe, $data, $sprache, null, null, false, $person->person_id);
		
		if (hasData($message))
		{
			$this->setRawData(
				'message','<div class=\'alert alert-success\'>'.
				sprintf(
					$this->getPhrase('Registration/EmailWithAccessCodeSent',
					$this->getData('sprache'),
					$this->config->item('root_oe')),
					$email
				).'</div>'
			);
		}
		else
		{
			$this->setRawData(
				'message',
				'<span class="alert alert-danger">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br />'
			);
			$this->_setError(true, $message->error . ' ' . $message->fhcCode);
		}
	}

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