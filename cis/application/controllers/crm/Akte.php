<?php

/**
 * 
 */
class Akte extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model AkteModel
		$this->load->model('crm/Akte_model', 'AkteModel');
	}
	
	/**
	 * 
	 */
	public function getAkten($person_id, $dokumenttyp_kurzbz = null)
	{
		$this->response($this->AkteModel->getAkten($person_id, $dokumenttyp_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getAktenAccepted($person_id, $dokumenttyp_kurzbz = null)
	{
		$this->response($this->AkteModel->getAktenAccepted($person_id, $dokumenttyp_kurzbz));
	}
}