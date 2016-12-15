<?php

/**
 * 
 */
class Adresse extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model AdresseModel
		$this->load->model('person/Adresse_model', 'AdresseModel');
	}
	
	/**
	 * 
	 */
	public function getAdresse($person_id)
	{
		$this->response($this->AdresseModel->getAdresse($person_id));
	}
}