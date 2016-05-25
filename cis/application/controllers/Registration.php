<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->library("securimage/securimage");
        $this->load->model("Person_model");
        $this->load->model("Kontakt_model");
        $this->lang->load('aufnahme', $this->get_language());
    }

    public function index()
    {
        $this->_data = array(
            "sprache" => $this->get_language(),
            "studiengang_kz" => $this->input->get('studiengang_kz'),
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
        //TODO		
        //$this->form_validation->set_rules("captcha_code", "Captcha", "required|max_length[6]|callback_check_captcha");


        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('registration',  $this->_data);
            $this->load->view('templates/footer');
        } else {
            $this->saveRegistration($this->_data);
        }
    }

    public function securimage() {
        $this->load->library('securimage');
        $img = new Securimage();
        $img->show(); // alternate use: $img->show('/path/to/background.jpg');
    }

    public function check_captcha() {
        $securimage = new Securimage();
        if (!$securimage->check($this->input->post("captcha_code"))) {
            $this->form_validation->set_message("check_captcha", "Code does not match.");
            return false;
        }
        return true;
    }

    public function check_email() {
        if (!($this->input->post("email") == $this->input->post("email2"))) {
            $this->form_validation->set_message("check_email", "E-Mail adresses do not match.");
            return false;
        }
        return true;
    }

    public function resendCode() {
        //TODO 
         $this->_data = array(
            "sprache" => $this->get_language()
        );

        if (($this->input->post("email") != null)) {
             $this->_data["email"] = $this->input->post("email");
            $this->Person_model->checkBewerbung(array("email" =>  $this->_data["email"]));

            var_dump($this->Person_model->result);
            if ($this->Person_model->result->error == 0) {
                if (count($this->Person_model->result->retval) > 0) {
                    $zugangscode = $this->Person_model->result->retval[0]->zugangscode;
                    $person_id = $this->Person_model->result->retval[0]->person_id;
                    $message = $this->resendMail($zugangscode,  $this->_data["email"], $person_id);
                    $this->_data["message"] = $message;
                }
            }
            else
            {
                //TODO could not load data
            }
        }

        $this->load->view('templates/header');
        $this->load->view('registration/resendCode',  $this->_data);
        $this->load->view('templates/footer');
    }

    public function confirm()
    {    
         $this->_data = array(
            "sprache" => $this->get_language()
        );
         
         if(isset($this->input->get()["studiengang_kz"]))
         {
             $this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];
         }

        $this->Person_model->checkZugangscodePerson(array("code" => $this->input->get("code")));
        if (($this->Person_model->result->error == 0) && (count($this->Person_model->result->retval) == 1)) {
            $person_id = $this->Person_model->result->retval[0]->person_id;
             $this->_data["zugangscode"] = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);

            if ($this->Kontakt_model->getKontakt($person_id)) {
                 $this->_data["email"] = $this->Kontakt_model->result->retval[0]->kontakt;
                $person = new stdClass();
                $person->person_id = $person_id;
                $this->Person_model->getPersonen($person_id);
                if (($this->Person_model->result->error == 0) && (count($this->Person_model->result->retval) == 1)) {
                    $person = $this->Person_model->result->retval[0];
                    //check if timestamp code is not older than 24 hours 
                    //TODO define timespan until invalidation of timestamp in config
                    if (strtotime(date('Y-m-d H:i:s')) < strtotime($person->zugangscode_timestamp . " +24 hours")) {
                        $person->zugangscode =  $this->_data["zugangscode"];
                        $this->Person_model->updatePerson($person);
                        $this->load->view('templates/header');
                        $this->load->view('login/confirm_login',  $this->_data);
                        $this->load->view('templates/footer');
                    } else {
                         $this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/codeNichtMehrGueltig') . '</span><br /><a href=' . base_url("index.dist.php/Login") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
                        $this->load->view('templates/header');
                        $this->load->view('login/confirm_error',  $this->_data);
                        $this->load->view('templates/footer');
                    }
                }
            }
        } elseif (empty($this->Person_model->result->data)) {
            $this->_data["zugangscode"] = "";
            $this->_data["message"] = '<span class="error">' . $this->lang->line('aufnahme/fehler') . '</span><br /><a href=' . base_url("index.dist.php/Login") . '>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
            $this->_data["email"] = "";
            $this->load->view('templates/header');
            $this->load->view('login/confirm_login',  $this->_data);
            $this->load->view('templates/footer');
        }
    }

    private function saveRegistration($data) {
        $zugangscode = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
        $person = new stdClass();
        $person->vorname = $data["vorname"];
        $person->nachname = $data["nachname"];
        $person->gebdatum = date('Y-m-d', strtotime($data["geb_datum"]));
        $person->zugangscode = $zugangscode;
        $person->zugangscode_timestamp = date('Y-m-d H:i:s');
        $person->insertvon = 'online';
        $person->vornamen = "";

        $this->Person_model->checkBewerbung(array("email" => $data["email"]));

        //TODO error handling
        if ($this->Person_model->result->error == 0) {
            if (count($this->Person_model->result->retval) > 0) {
                $data["message"] = '<p class="alert alert-danger" id="danger-alert">' . sprintf($this->lang->line('aufnahme/mailadresseBereitsGenutzt'), $data["email"]) . '</p>'
                        . '<a href="' . base_url("index.dist.php/Registration/resendCode") . '"><button type="submit" class="btn btn-primary">' . $this->lang->line('aufnahme/codeZuschicken') . '</button>'
                        . '<button type="submit" class="btn btn-primary" value="Nein" onclick="document.RegistrationLoginForm.email.value=\'\'; document.getElementById(\'RegistrationLoginForm\').submit();">' . $this->lang->line('global/abbrechen') . '</button>';
                $this->load->view('templates/header');
                $this->load->view('registration', $data);
                $this->load->view('templates/footer');
            } else {
                $this->Person_model->savePerson($person);

                //TODO error handling
                if ($this->Person_model->result->error == 0) {
                    $kontakt = new stdClass();
                    $kontakt->person_id = $this->Person_model->result->retval;
                    $kontakt->kontakttyp = "email";
                    $kontakt->kontakt = $data["email"];
                    $kontakt->insertamum = date('Y-m-d H:i:s');
                    $kontakt->insertvon = 'online';
                    $this->Kontakt_model->saveKontakt($kontakt);

                    //TODO error handling
                    if ($this->Kontakt_model->result->error == 0) {
                        $message = $this->sendMail($zugangscode, $data["email"], $kontakt->person_id, $data["studiengang_kz"]);
                        $data["message"] = $message;
                        $this->load->view('templates/header');
                        $this->load->view('registration', $data);
                        $this->load->view('templates/footer');
                    }
                    else
                    {
                        //TODO could not save kontakt
                    }
                }
                else
                {
                    //TODO could not save person
                }
            }
        }
    }

    private function sendMail($zugangscode, $email, $person_id = null, $studiengang_kz = "") {
        if ($person_id != '') {
            $this->Person_model->getPersonen($person_id);
            if ($this->Person_model->result->error == 0) {
                $person = $this->Person_model->result->retval[0];
                $vorname = $person->vorname;
                $nachname = $person->nachname;
                $geschlecht = $person->geschlecht;
            } else {
                return $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
            }
        }
        if ($geschlecht == 'm')
            $anrede = $this->lang->line('aufnahme/anredeMaennlich');
        else
            $anrede = $this->lang->line('aufnahme/anredeWeiblich');

        $this->load->library("mail", array("to" => $email, "from" => 'no-reply', "subject" => $this->lang->line('aufnahme/registration'), "text" => $this->lang->line('aufnahme/mailtextHtml')));
        $text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, $studiengang_kz);
        $this->mail->setHTMLContent($text);
        if (!$this->mail->send())
            $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
        else
            $msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . "<br><br><a href=" . base_url("index.dist.php/Login") . ">" . $this->lang->line('aufnahme/zurueckZurAnmeldung') . "</a>";

        return $msg;
    }

    private function resendMail($zugangscode, $email, $person_id = null) {
        if ($person_id != '') {
            $this->Person_model->getPersonen($person_id);
            if ($this->Person_model->result->error == 0) {
                $person = $this->Person_model->result->retval[0];
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
        $text = sprintf($this->lang->line('aufnahme/mailtext'), $vorname, $nachname, $zugangscode, $anrede, NULL);
        $this->mail->setHTMLContent($text);
        if (!$this->mail->send())
            $msg = '<span class="error">' . $this->lang->line('aufnahme/fehlerBeimSenden') . '</span><br /><a href=' . $_SERVER['PHP_SELF'] . '?method=registration>' . $this->lang->line('aufnahme/zurueckZurAnmeldung') . '</a>';
        else
            $msg = sprintf($this->lang->line('aufnahme/emailgesendetan'), $email) . "<br><br><a href=" . $_SERVER['PHP_SELF'] . ">" . $this->lang->line('aufnahme/zurueckZurAnmeldung') . "</a>";

        return $msg;
    }

    public function code_login()
    {
        $studiengang_kz = $this->input->get()["studiengang_kz"];
        $code = $this->input->post("password");
        $email = $this->input->post("email");
        $this->Person_model->getPersonFromCode($code, $email);

        if ($this->Person_model->result->error == 0) {
            $data['person'] = $this->Person_model->result->retval[0];
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
            } else
                $data['wrong_code'] = true;
        }
        else {
            //TODO view error
        }
    }

}
