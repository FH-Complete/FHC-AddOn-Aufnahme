<?php
/**
 * ./cis/application/controllers/Registration.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class Registration extends MY_Controller {

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
	public function __construct() {
		parent::__construct();
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->load->library("securimage/securimage");
		$this->load->model("Person_model", "PersonModel");
		$this->load->model("Kontakt_model");
		$this->load->model("Message_model", "MessageModel");
		$this->load->model('adresse_model', "AdresseModel");
		$this->lang->load('aufnahme', $this->get_language());
		$this->lang->load('login', $this->get_language());
		$this->lang->load('registration', $this->get_language());
	}


	/**
	 *
	 */
	public function index() {
		$this->_data = array(
			"sprache" => $this->get_language(),
			"studiengang_kz" => $this->input->get('studiengang_kz'),
			"vorname" => $this->input->post("vorname"),
			"nachname" => $this->input->post("nachname"),
			"geb_datum" => $this->input->post("geb_datum"),
			"email" => $this->input->post("email"),
			"captcha_code" => $this->input->post("captcha_code"),
			"zugangscode" => $this->input->post("zugangscode"),
			//     "wohnort" =>$this->input->post("wohnort"),
			"geschlecht" => $this->input->post("geschlecht")
		);

		if (isset($this->input->get()["sprache"])) {
			$this->lang->load('aufnahme', $this->_data["sprache"]);
			$this->lang->load('login', $this->_data["sprache"]);
			$this->_data["sprache"] = $this->get_language();
		}

		//form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
		$this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
		$this->form_validation->set_rules("geb_datum", "Geburtsdatum", "required|callback_check_date");
		// $this->form_validation->set_rules("wohnort", "Wohnort", "required");
		$this->form_validation->set_rules("email", "E-Mail", "required|valid_email");
		$this->form_validation->set_rules("email2", "E-Mail", "required|valid_email|callback_check_email");
		$this->form_validation->set_rules("datenschutz", "Datenschutz", "callback_check_terms");
		$this->form_validation->set_rules("captcha_code", "Captcha", "required|max_length[6]|callback_check_captcha");


		if ($this->form_validation->run() == FALSE) {
			$this->load->view('registration',  $this->_data);
		}
		else {
			$this->saveRegistration($this->_data);
		}
	}


	/**
	 *
	 * @param unknown $random (optional)
	 */
	public function securimage($random=null) {
		$this->load->library('securimage');
		$img = new Securimage();
		$img->show(); // alternate use: $img->show('/path/to/background.jpg');
	}


	/**
	 *
	 * @return unknown
	 */
	public function check_captcha() {
		if ($this->input->post("email") != $this->config->config["codeception_email"]) {
			$securimage = new Securimage();
			if (!$securimage->check($this->input->post("captcha_code"))) {
				//$this->form_validation->set_message("check_captcha", "Code does not match.");
				$this->form_validation->set_rules("captcha_code", "E-Captcha", "check_captcha");
				return false;
			}
		}
		return true;
	}


	/**
	 *
	 * @return unknown
	 */
	public function check_email() {
		if (!($this->input->post("email") == $this->input->post("email2"))) {
			//$this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
			$this->form_validation->set_rules("email2", "E-Mail", "check_email");
			return false;
		}
		return true;
	}


	public function check_terms() {
		if (($this->input->post("datenschutz") !== "")) {
			$this->form_validation->set_message("check_terms", "Sie müssen die Datenschutzbedingungen akzeptieren.");
			return false;
		}
		return true;
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function check_date() {
		$date = explode(".", $this->input->post("geb_datum"));
		if (!checkdate($date[1], $date[0], $date[2])) {
			//$this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
			$this->form_validation->set_message("check_date", "Bitte geben Sie ein gültiges Datum an.");
			return false;
		}
		return true;
	}


	public function resendCode() {
		$this->_data = array(
			"sprache" => $this->get_language()
		);
		
		if (($this->input->post("email") != null) && ($this->input->post("email") != "")) {
			$this->_data["email"] = $this->input->post("email");
			$bewerbung = $this->_checkBewerbung($this->_data["email"]);

			if (count($bewerbung) === 1) {
				$person = $bewerbung[0];
				$person = $this->_getPerson($person->person_id);

				$person->zugangscode_timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +".$this->config->item('invalidateResendTimestampAfter')." hour"));
				$person->zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
				$this->_savePerson($person);
				//$this->PersonModel->savePerson($person);

				$message = $this->resendMail($person->zugangscode,  $this->_data["email"], $person->person_id);
				$this->_data["message"] = $message;
			}
			else {
				$this->_setError(true, $this->lang->line("aufnahme/eMailAdresseNichtEindeutig"));
			}
		}
		elseif(isset($this->input->get()["email"]))
		{
			$this->_data["email"] = $this->input->get()["email"];
		}
		else {
			if (!empty($this->input->post())) {
				$this->_setError(true, $this->lang->line("aufnahme/eMailAdresseFehlt"));
			}
		}

//		$this->load->view('templates/header');
		$this->load->view('registration/resendCode', $this->_data);
//		$this->load->view('templates/footer');
	}


	public function confirm() {
		$this->_data = array(
			"sprache" => $this->get_language()
		);

		if (isset($this->input->get()["studiengang_kz"])) {
			$this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];
		}
		
		if(!isset($this->session->userdata()["zugangscode"]))
		{
			$this->session->set_userdata("zugangscode", $this->input->get("code"));
		}
		
		if(isset($this->input->get()["email"]))
		{
			$this->_data["email"] = $this->input->get()["email"];
		}
		else
		{
			if(isset($this->session->userdata()["zugangscode"]))
			{
				$this->session->sess_destroy();
			}
			$this->_data["zugangscode"] = "";
			$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehler') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			$this->_data["email"] = "";
			$this->load->view('login/confirm_login',  $this->_data);
		}
		
		$this->PersonModel->getPersonFromCode($this->session->userdata()["zugangscode"], $this->_data["email"]);
		$result = $this->PersonModel->result->retval;
		if (($this->PersonModel->isResultValid() === true) && (count($result) == 1)) {
			$person_id = $result[0]->person_id;
//			if ($this->Kontakt_model->getKontakt($person_id)) {
//				foreach($this->Kontakt_model->result->retval as $kontakt)
//				{
//					if($kontakt->kontakttyp == "email")
//					{
//						$this->_data["email"] = $kontakt->kontakt;
//					}
//				}
				
				$person = new stdClass();
				$person->person_id = $person_id;
				$result = $this->_getPerson($person_id);

				if (($this->PersonModel->isResultValid() === true) && (count($result) == 1)) {
					$person = $result;
					//check if timestamp code is not older than now
					if (strtotime(date('Y-m-d H:i:s')) < strtotime($person->zugangscode_timestamp)) {
						$this->_data["zugangscode"] = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
						$this->session->set_userdata("zugangscode", $this->_data["zugangscode"]);
						$person->zugangscode =  $this->_data["zugangscode"];
						$this->PersonModel->updatePerson($person);
//						$this->load->view('templates/header');
						$this->load->view('login/confirm_login',  $this->_data);
//						$this->load->view('templates/footer');
					}
					else {
						$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/codeNichtMehrGueltig') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
//						$this->load->view('templates/header');
						$this->load->view('login/confirm_error',  $this->_data);
//						$this->load->view('templates/footer');
					}
				}
				else {
					//error msg already set
				}
//			}
		}
		elseif (empty($this->PersonModel->result->data)) {
			if(isset($this->session->userdata()["zugangscode"]))
			{
				$this->session->sess_destroy();
			}
			$this->_data["zugangscode"] = "";
			$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehler') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			$this->_data["email"] = "";
//			$this->load->view('templates/header');
			$this->load->view('login/confirm_login',  $this->_data);
//			$this->load->view('templates/footer');
		}
	}


	private function saveRegistration($data) {
		$zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
		$person = new stdClass();
		$person->vorname = $data["vorname"];
		$person->nachname = $data["nachname"];
		$person->gebdatum = date('Y-m-d', strtotime($data["geb_datum"]));
		$person->zugangscode = $zugangscode;
		//set timestamp which is indicated how long the code is valid
		$person->zugangscode_timestamp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +".$this->config->item('invalidateRegistrationTimestampAfter')." hours"));
		$person->insertvon = 'online';
		$person->vornamen = "";
		$person->aktiv = "t";
		$person->geschlecht = "u";
		$person->sprache = ucfirst($this->_data["sprache"]);

		$bewerbung = $this->_checkBewerbung($this->_data["email"]);

		//TODO error handling
		if ($this->PersonModel->isResultValid() === true) {
			if (count($bewerbung) > 0) {
				$data["message"] = '<p class="alert alert-danger" id="danger-alert">' . sprintf($this->lang->line('aufnahme/mailadresseBereitsGenutzt'), $data["email"]) . '</p>'
					. '<a href="' . base_url("index.dist.php/Registration/resendCode?email=".$this->_data["email"]) . '"><button type="submit" class="btn btn-primary">' . $this->lang->line('aufnahme/codeZuschicken') . '</button></a>';
				$this->load->view('registration', $data);
			}
			else {
				$person_id = $this->_savePerson($person);
				//TODO error handling
				if ($this->PersonModel->isResultValid() === true) {
					$kontakt = new stdClass();
					$kontakt->person_id = $person_id;
					$kontakt->kontakttyp = "email";
					$kontakt->kontakt = $data["email"];
					$kontakt->insertamum = date('Y-m-d H:i:s');
					$kontakt->insertvon = 'online';
					$kontakt->zustellung = true;
					$this->Kontakt_model->saveKontakt($kontakt);

					//TODO error handling
					if ($this->Kontakt_model->isResultValid() === true) {
						//$message = $this->sendMail($zugangscode, $data["email"], $person_id, $data["studiengang_kz"]);
						$this->_data["person"] = $this->_getPerson($person_id);

						if ($this->PersonModel->isResultValid() === true)
						{
							$this->_sendMessageVorlage($this->_data["person"], $zugangscode, base_url($this->config->config["index_page"]."/Registration/confirm?code=".$zugangscode."&studiengang_kz=".$data['studiengang_kz']), $data["email"]);

							//$data["message"] = $message;
							//       $this->load->view('templates/header');
							$this->_data["success"] = true;
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
					//      $adresse->heimatadresse = "t";
					//      $adresse->zustelladresse = "f";
					////      $adresse->ort = $data["wohnort"];
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
			//error message already set
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	public function code_login()
	{
		$studiengang_kz = $this->input->get()["studiengang_kz"];
		$code = $this->input->post("password");
		$email = $this->input->post("email");
		$this->PersonModel->getPersonFromCode($code, $email);

		if ($this->PersonModel->isResultValid() === true) {
			if((isset($this->PersonModel->result->retval)) && (count($this->PersonModel->result->retval) == 1))
			{
				$data['person'] = $this->PersonModel->result->retval[0];
				if (isset($data['person']->person_id))
				{
					$this->session->set_userdata("person_id", $data['person']->person_id);
					if((isset($studiengang_kz)) && ($studiengang_kz != ""))
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


	private function sendMail($zugangscode, $email, $person_id = null, $studiengang_kz = "") {
		if ($person_id != '') {
			$this->PersonModel->getPersonen($person_id);
			if ($this->PersonModel->result->error == 0) {
				$person = $this->PersonModel->result->retval[0];
				$vorname = $person->vorname;
				$nachname = $person->nachname;
				$geschlecht = $person->geschlecht;
			} else {
				return $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
		}
		if ($geschlecht == 'm')
			$anrede = $this->lang->line('aufnahme/anredeMaennlich');
		elseif($geschlecht == 'w')
			$anrede = $this->lang->line('aufnahme/anredeWeiblich');
		else
			$anrede = $this->lang->line('aufnahme/anredeUnknown');

		$this->load->library("mail", array("to" => $email, "from" => 'no-reply', "subject" => $this->lang->line('aufnahme/registration'), "text" => $this->lang->line('aufnahme/mailtextHtml')));
		$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, $studiengang_kz);
		$this->mail->setHTMLContent($text);
		if (!$this->mail->send())

			$msg = '<span class="error">' . $this->getPhrase('Registration/EmailAddressTaken', $this->_data['sprache']) . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
		else
			$msg = sprintf($this->getPhrase('Registration/EmailWithAccessCodeSent', $this->_data['sprache']), $email) . "<br><br><a href=" . base_url("index.dist.php") . ">" . $this->lang->line('aufnahme/zurueckZurAnmeldung') . "</a>";

		return $msg;
	}


	private function resendMail($zugangscode, $email, $person_id = null) {
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

		$this->load->library("mail", array("to" => $email, "from" => 'no-reply', "subject" => $this->lang->line('aufnahme/registration'), "text" => $this->lang->line('aufnahme/mailtextHtml')));
		$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, NULL, $email);
		$this->mail->setHTMLContent($text);
		if (!$this->mail->send())
			$msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
		else
			$msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . '<br><br><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';

		return $msg;
	}


	private function _sendMessageVorlage($person, $code, $link, $email)
	{
		$data = array(
			"anrede" => (is_null($person->anrede)) ? "" : $person->anrede,
			"vorname" => $person->vorname,
			"nachname" => $person->nachname,
			"code" => $code,
			"link" => $link,
			"eMailAdresse" => $email,
		);

		if ($this->config->item('root_oe'))
			$oe = $this->config->item('root_oe');
		else
			$oe = 'fhstp';

		(isset($person->sprache) && ($person->sprache !== null)) ? $sprache = $person->sprache : $sprache = $this->_data["sprache"];

		$this->MessageModel->sendMessageVorlage("MailRegistrationConfirmation", $oe, $data, $sprache, $orgform_kurzbz=null, null, $person->person_id);

		if($this->MessageModel->isResultValid() === true)
		{
			if((isset($this->MessageModel->result->error)) && ($this->MessageModel->result->error === 0))
			{
				$this->_data["message"] = sprintf($this->getPhrase('Registration/EmailWithAccessCodeSent', $this->_data['sprache']), $email) . '<br><br><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
			else
			{
				$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			}
		}
		else
		{
			$this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . base_url("index.dist.php") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}


	private function _getPerson($person_id)
	{
		$this->PersonModel->getPersonen($person_id);
		if($this->PersonModel->isResultValid() === true)
		{
			return $this->PersonModel->result->retval[0];
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	private function _savePerson($person)
	{
		$this->PersonModel->savePerson($person);
		if($this->PersonModel->isResultValid() === true)
		{
			return $this->PersonModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	private function _checkBewerbung($email)
	{
		$this->PersonModel->checkBewerbung(array("email" => $email));
		if($this->PersonModel->isResultValid() === true)
		{
			return $this->PersonModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


	private function _checkZugangscodePerson($code)
	{
		$this->PersonModel->checkZugangscodePerson(array("code" => $code));
		if($this->PersonModel->isResultValid() === true)
		{
			return $this->PersonModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}

}
