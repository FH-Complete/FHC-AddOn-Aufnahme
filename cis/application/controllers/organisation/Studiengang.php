<?php

/**
 * 
 */
class Studiengang extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model StudiengangModel
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
	}
	
	/**
	 * 
	 */
	public function getAllForBewerbung()
	{
		$this->response($this->StudiengangModel->getAllForBewerbung());
	}
	
	/**
	 * 
	 */
	public function getStudiengang($studiengang_kz)
	{
		$this->response($this->StudiengangModel->getStudiengang($studiengang_kz));
	}
	
	/**
	 * 
	 */
	public function getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester)
	{
		$this->response($this->StudiengangModel->getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester));
	}
	
	/**
	 * 
	 */
	public function getStudiengangBewerbung()
	{
		$this->response($this->StudiengangModel->getStudiengangBewerbung());
	}
}