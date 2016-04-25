<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller 
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
		$this->load->model('person_model');
    }
	
	public function index()
	{
		$data['sprache'] = $this->get_language();
		$data['stg_kz'] = $this->input->get('stg_kz');
		if ($data['stg_kz'])
		    $this->session->stg_kz = $data['stg_kz'];
		$data['username'] = $this->input->post('username');
		$data['password'] = $this->input->post('password');
		$data['email'] = $this->input->post('email');
		// First _POST then _GET
		$data['code'] = $this->input->post('code') ? $this->input->post('code') : $this->input->get('code');

		if ($data['code'])
		    $this->code_login($data['code'], $data, $data["email"]);
		elseif($data['username'] && $data["password"])
		{
		    var_dump($this->input->post());
		}
		else
		{
		    // $this->load->view('templates/header');
		    $this->load->view('login',$data);
		    // $this->load->view('templates/footer');
		}
	}

	private function code_login($code, &$data, $email=null)
	{
		$this->person_model->getPersonFromCode($code, $email);
		$data['person'] = $this->person_model->result;
		if (isset($data['person']->data[0]->person_id))
		{
		    $this->session->person_id=$data['person']->data[0]->person_id;
		    redirect('/Aufnahme');
		}
		else
		    $data['wrong_code'] = true;
	}
}


