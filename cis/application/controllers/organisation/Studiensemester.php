<?php

/**
 * 
 */
class Studiensemester extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model StudiensemesterModel
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
	}
	
	/**
	 * 
	 */
	public function getNextStudiensemester($art = null)
	{
		$this->response($this->StudiensemesterModel->getNextStudiensemester($art));
	}
}