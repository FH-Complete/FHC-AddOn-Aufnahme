<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

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
        $this->load->model('person_model');
    }

    public function index()
    {    
        if(isset($this->input->get()["studiengang_kz"]))
        {
            $this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];
        }
        
        $this->_data['sprache'] = $this->get_language();
        $this->_data['studiengang_kz'] = $this->input->get('studiengang_kz');
        if ($this->_data['studiengang_kz'])
        {
            $this->session->set_userdata("studiengang_kz", $this->_data['studiengang_kz']);
        }
        $this->_data['username'] = $this->input->post('username');
        $this->_data['password'] = $this->input->post('password');
        $this->_data['email'] = $this->input->post('email');
        // First _POST then _GET
        $this->_data['code'] = $this->input->post('code') ? $this->input->post('code') : $this->input->get('code');

        if ($this->_data['code'])
            $this->code_login($this->_data['code'], $this->_data, $this->_data["email"]);
        elseif ($this->_data['username'] && $this->_data["password"]) {
            var_dump($this->input->post());
        } else {
            // $this->load->view('templates/header');
            $this->load->view('login', $this->_data);
            // $this->load->view('templates/footer');
        }
    }

    private function code_login($code, &$data, $email = null) {
        $this->person_model->getPersonFromCode($code, $email);
        if(($this->person_model->result->error == 0) && (count($this->person_model->result->retval) == 1))
        {
            $data['person'] = $this->person_model->result->retval[0];
            if (isset($data['person']->person_id)) {
                $this->session->set_userdata("person_id", $data['person']->person_id);
                if(isset($this->_data["studiengang_kz"]))
                {
                    $this->session->set_userdata("studiengang_kz", $this->_data["studiengang_kz"]);
                    redirect('/Bewerbung/'.$this->_data["studiengang_kz"]);
                }
                redirect('/Studiengaenge');
            } else {
                //TODO error
            }
        }
    }

}
