<?php

/**
 * 
 */
class Prestudent extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model PrestudentModel
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
	}
	
	/**
	 * 
	 */
	public function getPrestudent($prestudent_id)
	{
		$this->response($this->PrestudentModel->getPrestudent($prestudent_id));
	}
	
	/**
	 * 
	 */
	public function getPrestudentByPersonId($person_id)
	{
		$this->response($this->PrestudentModel->getPrestudentByPersonId($person_id));
	}
	
	/**
	 * 
	 */
	public function getSpecialization($prestudent_id, $titel)
	{
		$this->response($this->PrestudentModel->getSpecialization($prestudent_id, $titel));
	}
}