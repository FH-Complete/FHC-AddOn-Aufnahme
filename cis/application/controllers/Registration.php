<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MY_Controller
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
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
		parent::__construct();
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->load->library("securimage/securimage");
		$this->load->model("Person_model");
		$this->load->model("Kontakt_model");
		$this->lang->load('global', $this->get_language());
		$this->lang->load('aufnahme', $this->get_language());
    }

    public function index()
    {
		$data = array(
			"sprache" => $this->get_language(),
			"stg_kz" => $this->input->get('stg_kz'),
			"vorname" => $this->input->post("vorname"),
			"nachname" => $this->input->post("nachname"),
			"geb_datum" => $this->input->post("geb_datum"),
			"email" => $this->input->post("email"),
			"captcha_code" => $this->input->post("captcha_code"),
			"zugangscode" => $this->input->post("zugangscode")
		);

		//form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("vorname", "Vorname", "required|max_length[32]");
		$this->form_validation->set_rules("nachname", "Nachname", "required|max_length[64]");
		$this->form_validation->set_rules("geb_datum", "Geburtsdatum", "required");
		$this->form_validation->set_rules("email", "E-Mail", "required|valid_email");
		$this->form_validation->set_rules("email2", "E-Mail", "required|valid_email|callback_check_email");
		$this->form_validation->set_rules("captcha_code", "Captcha", "required|max_length[6]|callback_check_captcha");


		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('registration', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			$this->saveRegistration($data);
		}
    }

    public function securimage()
    {
		$this->load->library('securimage');
		$img = new Securimage();
		$img->show(); // alternate use: $img->show('/path/to/background.jpg');
    }

    public function check_captcha()
    {
		$securimage = new Securimage();
		if (!$securimage->check($this->input->post("captcha_code")))
		{
			$this->form_validation->set_message("check_captcha", "Code does not match.");
			return false;
		}
		return true;
    }
	
	public function check_email()
	{
		if(!($this->input->post("email") == $this->input->post("email2")))
		{
			$this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
			return false;
		}
		return true;
	}

    public function resendCode()
    {
		//TODO
		$data = array(
			"sprache" => $this->get_language()
		);

		if (($this->input->post("email") != null))
		{
			$data["email"] = $this->input->post("email");
			$this->Person_model->checkBewerbung(array("email" => $data["email"]));

			if ($this->Person_model->result->success)
			{
			if(count($this->Person_model->result->data) > 0)
			{
				$zugangscode = $this->Person_model->result->data[0]->zugangscode;
				$person_id = $this->Person_model->result->data[0]->person_id;
				$message = $this->resendMail($zugangscode, $data["email"], $person_id);
				$data["message"] = $message;
			}
			}
		}

		$this->load->view('templates/header');
		$this->load->view('registration/resendCode', $data);
		$this->load->view('templates/footer');
    }

    public function confirm()
    {
		$data = array(
			"sprache" => $this->get_language()
		);

		$this->Person_model->checkZugangscodePerson(array("code" => $this->input->get("code")));
		if ($this->Person_model->result->success && (count($this->Person_model->result->data) == 1))
		{   
			$person_id = $this->Person_model->result->data[0]->person_id;
			$data["zugangscode"] = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);

			if ($this->Kontakt_model->getKontaktPerson($person_id))
			{
			$data["email"] = $this->Kontakt_model->result->data[0]->kontakt;
			$person = new stdClass();
			$person->person_id = $person_id;
			$this->Person_model->getPersonen($person_id);
			if ($this->Person_model->result->success && (count($this->Person_model->result->data) == 1))
			{
				$person = $this->Person_model->result->data;
				//check if timestamp code is not older than 24 hours 
				if(strtotime(date('Y-m-d H:i:s')) < strtotime($person->zugangscode_timestamp." +24 hours"))
				{
				$person->zugangscode = $data["zugangscode"];
				$this->Person_model->updatePerson($person);
				$this->load->view('templates/header');
				$this->load->view('login/confirm_login', $data);
				$this->load->view('templates/footer');
				}
				else
				{
				$data["message"] = '<span class="error">' . $this->lang->line('aufnahme/codeNichtMehrGueltig') . '</span><br /><a href=' . base_url("index.dist.php/Login") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
				$this->load->view('templates/header');
				$this->load->view('login/confirm_error', $data);
				$this->load->view('templates/footer');
				}
			}
			}
		}
		elseif (empty($this->Person_model->result->data))
		{
			$data["zugangscode"] = "";
			$data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehler') . '</span><br /><a href=' . base_url("index.dist.php/Login") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
			$data["email"] = "";
			$this->load->view('templates/header');
			$this->load->view('login/confirm_login', $data);
			$this->load->view('templates/footer');
		}
    }

    private function saveRegistration($data)
    {
	$zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
	$person = new stdClass();
	$person->vorname = $data["vorname"];
	$person->nachname = $data["nachname"];
	$person->gebdatum = date('Y-m-d', strtotime($data["geb_datum"]));
	$person->zugangscode = $zugangscode;
	$person->insertvon = 'online';

	$this->Person_model->checkBewerbung(array("email" => $data["email"]));
	
	if ($this->Person_model->result->success)
	{
	    if (count($this->Person_model->result->data) > 0)
	    {
		$data["message"] = '<p class="alert alert-danger" id="danger-alert">' . sprintf($this->lang->line('aufnahme/mailadresseBereitsGenutzt'), $data["email"]) . '</p>'
			. '<a href="' . base_url("index.dist.php/Registration/resendCode") . '"><button type="submit" class="btn btn-primary">' . $this->lang->line('aufnahme/codeZuschicken') . '</button>'
			. '<button type="submit" class="btn btn-primary" value="Nein" onclick="document.RegistrationLoginForm.email.value=\'\'; document.getElementById(\'RegistrationLoginForm\').submit();">' . $this->lang->line('global/abbrechen') . '</button>';
		$this->load->view('templates/header');
		$this->load->view('registration', $data);
		$this->load->view('templates/footer');
	    }
	    else
	    {
		$this->Person_model->savePerson($person);

		if ($this->Person_model->result->success)
		{
		    $kontakt = new stdClass();
		    $kontakt->person_id = $this->Person_model->result->data;
		    $kontakt->kontakttyp = "email";
		    $kontakt->kontakt = $data["email"];
		    $kontakt->insertamum = date('Y-m-d H:i:s');
		    $kontakt->insertvon = 'online';
		    $this->Kontakt_model->saveKontakt($kontakt);
		    
		    if ($this->Kontakt_model->result->success)
		    {
			$message = $this->sendMail($zugangscode, $data["email"], $kontakt->person_id, $data["stg_kz"]);
			$data["message"] = $message;
			$this->load->view('templates/header');
			$this->load->view('registration', $data);
			$this->load->view('templates/footer');
		    }
		}
	    }
	}
    }

    private function sendMail($zugangscode, $email, $person_id = null, $stg_kz="")
    {
	if ($person_id != '')
	{
	    $this->Person_model->getPersonen($person_id);
	    if ($this->Person_model->result->success)
	    {
		$person = $this->Person_model->result->data;
		$vorname = $person->vorname;
		$nachname = $person->nachname;
		$geschlecht = $person->geschlecht;
	    }
	    else
	    {
		return $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
	    }
	}
	if ($geschlecht == 'm')
	    $anrede = $this->lang->line('aufnahme/anredeMaennlich');
	else
	    $anrede = $this->lang->line('aufnahme/anredeWeiblich');

	$this->load->library("mail", array("to" => $email, "from" => 'no-reply', "subject" => $this->lang->line('aufnahme/registration'), "text" => $this->lang->line('aufnahme/mailtextHtml')));
	$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, $stg_kz);
	$this->mail->setHTMLContent($text);
	if (!$this->mail->send())
	    $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
	else
	    $msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . "<br><br><a href=" . base_url("index.dist.php/Login") . ">" . $this->lang->line('aufnahme/zurueckZurAnmeldung') . "</a>";

	return $msg;
    }

    private function resendMail($zugangscode, $email, $person_id = null)
    {
	if ($person_id != '')
	{
	    $this->Person_model->getPersonen($person_id);
	    if ($this->Person_model->result->success)
	    {
		$person = $this->Person_model->result->data;
		$vorname = $person->vorname;
		$nachname = $person->nachname;
		$geschlecht = $person->geschlecht;
	    }
	}
	
	if ($geschlecht == 'm')
	    $anrede = $this->lang->line('aufnahme/anredeMaennlich');
	else
	    $anrede = $this->lang->line('aufnahme/anredeWeiblich');

	$this->load->library("mail", array("to" => $email, "from" => 'no-reply', "subject" => $this->lang->line('aufnahme/registration'), "text" => $this->lang->line('aufnahme/mailtextHtml')));
	$text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, NULL);
	$this->mail->setHTMLContent($text);
	if (!$this->mail->send())
	    $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
	else
	    $msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . "<br><br><a href=" . $_SERVER['PHP_SELF'] . ">" . $this->lang->line('aufnahme/zurueckZurAnmeldung') . "</a>";

	return $msg;
    }
    
    public function code_login()
    {
	$code = $this->input->post("password");
	$email = $this->input->post("email");
	$this->Person_model->getPersonFromCode($code, $email);
	$data['person'] = $this->Person_model->result;
	if (isset($data['person']->data[0]->person_id))
	{
	    $this->session->person_id=$data['person']->data[0]->person_id;
	    redirect('/Aufnahme');
	}
	else
	    $data['wrong_code'] = true;
    }

}
