<?php

/**
 * 
 */
class Prestudentstatus extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model PrestudentstatusModel
		$this->load->model('crm/Prestudentstatus_model', 'PrestudentstatusModel');
	}
	
	/**
	 * 
	 */
	public function getPrestudentstatus($ausbildungssemester, $studiensemester_kurzbz, $status_kurzbz, $prestudent_id)
	{
		$this->response(
			$this->PrestudentstatusModel->getPrestudentstatus(
				$ausbildungssemester,
				$studiensemester_kurzbz,
				$status_kurzbz,
				$prestudent_id
			)
		);
	}
	
	/**
	 * 
	 */
	public function getLastStatus($prestudent_id, $studiensemester_kurzbz = null, $status_kurzbz = null)
	{
		$this->response($this->PrestudentstatusModel->getLastStatus($prestudent_id, $studiensemester_kurzbz, $status_kurzbz));
	}
}