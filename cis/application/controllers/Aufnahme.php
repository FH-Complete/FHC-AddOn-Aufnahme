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
		$data['sprache'] = $this->get_language();
		$data['person']=$this->person_model->get_person($this->session->person_id);

		$this->load->view('header');
		$this->load->view('aufnahme',$data);
		$this->load->view('footer');
	}

}

