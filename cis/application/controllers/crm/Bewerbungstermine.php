<?php

/**
 * 
 */
class Bewerbungstermine extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model BewerbungstermineModel
		$this->load->model('crm/Bewerbungstermine_model', 'BewerbungstermineModel');
	}
	
	/**
	 * 
	 */
	public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz)
	{
		$this->response($this->BewerbungstermineModel->getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getCurrent()
	{
		$this->response($this->BewerbungstermineModel->getCurrent());
	}
	
	/**
	 * 
	 */
	public function getByStudienplan($studienplan_id)
	{
		$this->response($this->BewerbungstermineModel->getByStudienplan($studienplan_id));
	}
}