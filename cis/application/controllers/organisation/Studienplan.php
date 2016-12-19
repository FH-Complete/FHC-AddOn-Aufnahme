<?php

/**
 * 
 */
class Studienplan extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model StudienplanModel
		$this->load->model('organisation/Studienplan_model', 'StudienplanModel');
	}
	
	/**
	 * 
	 */
	public function getStudienplan($studienplan_id)
	{
		$this->response($this->StudienplanModel->getStudienplan($studienplan_id));
	}
	
	/**
	 * 
	 */
	public function getStudienplaene($studiengang_kz)
	{
		$this->response($this->StudienplanModel->getStudienplaene($studiengang_kz));
	}
	
	/**
	 * 
	 */
	public function getStudienplaeneFromSem($studiengang_kz, $studiensemester_kurzbz, $ausbildungssemester = null, $orgform_kurzbz = null)
	{
		$this->response(
			$this->StudienplanModel->getStudienplaeneFromSem(
				$studiengang_kz,
				$studiensemester_kurzbz,
				$ausbildungssemester,
				$orgform_kurzbz
			)
		);
	}
}