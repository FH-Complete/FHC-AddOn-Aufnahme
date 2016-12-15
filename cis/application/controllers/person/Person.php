<?php

/**
 * 
 */
class Person extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model PersonModel
		$this->load->model('person/Person_model', 'PersonModel');
	}
	
	/**
	 * 
	 */
	public function getPerson($person_id = null, $code = null, $email = null)
	{
		$this->response($this->PersonModel->getPerson($person_id, $code, $email));
	}
	
	/**
	 * 
	 */
	public function checkBewerbung($email, $studiensemester_kurzbz = null)
	{
		$this->response($this->PersonModel->checkBewerbung(email, studiensemester_kurzbz));
	}
}