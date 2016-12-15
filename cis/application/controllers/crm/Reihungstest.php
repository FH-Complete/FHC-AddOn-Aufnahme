<?php

/**
 * 
 */
class Reihungstest extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model ReihungstestModel
		$this->load->model('crm/Reihungstest_model', 'ReihungstestModel');
	}
	
	/**
	 * 
	 */
	public function getReihungstest($reihungstest_id)
	{
		$this->response($this->ReihungstestModel->getReihungstest($reihungstest_id));
	}
	
	/**
	 * 
	 */
	public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz = null, $available = null)
	{
		$this->response(
			$this->ReihungstestModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz, $available)
		);
	}
	
	/**
	 * 
	 */
	public function getReihungstestByPersonId($person_id, $available = null)
	{
		$this->response($this->ReihungstestModel->getReihungstestByPersonId($person_id, $available));
	}
}