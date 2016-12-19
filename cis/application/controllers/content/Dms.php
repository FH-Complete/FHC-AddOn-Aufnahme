<?php

/**
 * 
 */
class Dms extends JSON_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model DmsModel
		$this->load->model('content/Dms_model', 'DmsModel');
	}
	
	/**
	 * 
	 */
	public function getDms($dms_id)
	{
		$this->response($this->DmsModel->getDms($dms_id));
	}
}