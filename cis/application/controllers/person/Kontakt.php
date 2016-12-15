<?php

/**
 * 
 */
class Kontakt extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model KontaktModel
		$this->load->model('person/Kontakt_model', 'KontaktModel');
	}
	
	/**
	 * 
	 */
	public function getOnlyKontaktByPersonId($person_id)
	{
		$this->response($this->KontaktModel->getOnlyKontaktByPersonId($person_id));
	}
}