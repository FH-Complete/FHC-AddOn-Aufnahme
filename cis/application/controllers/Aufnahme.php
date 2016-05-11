<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aufnahme extends MY_Controller 
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
		$this->checkSession();
		$this->load->helper("form");
		$this->load->library("form_validation");
		//$this->lang->load('global', $this->get_language());
		$this->lang->load('aufnahme', $this->get_language());
		$data['sprache'] = $this->get_language();
		$data['person']=$this->person_model->getPersonen($this->session->person_id);
		$data['tab'] = ($this->input->get("tab")!= null)? $this->input->get("tab"): "studiengaenge";
		$data["tabs"] = $this->config->item("aufnahme_tabs");
		$studiengaenge[] = array("stgkz"=>1,"bezeichnung"=>"Teststudiengang 1");
		$studiengaenge[] = array("stgkz"=>2,"bezeichnung"=>"Teststudiengang 2");
		$data["studiengaenge"] = $studiengaenge;
		
		//TODO fetch data from webservice
		$data["nation"] = array();
		$data["plz"] = array();
		$data["bundesland"] = array();

		//loading views
		$this->load->view('templates/header');
		$this->load->view('aufnahme',$data);
		$this->load->view('templates/footer');
	}
	
	private function checkSession()
	{
		//TODO
//		if(!isset($this->session->person_id))
//		{
//			redirect('/Login');
//		}
	}

}


