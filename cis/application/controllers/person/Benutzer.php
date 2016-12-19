<?php

/**
 * 
 */
class Benutzer extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model BenutzerModel
		$this->load->model('person/Benutzer_model', 'BenutzerModel');
	}
	
	/**
	 * 
	 */
	public function getBenutzer($uid)
	{
		$this->response($this->BenutzerModel->getBenutzer($uid));
	}
}