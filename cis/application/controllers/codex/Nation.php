<?php

/**
 * 
 */
class Nation extends JSON_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model NationModel
		$this->load->model('codex/Nation_model', 'NationModel');
	}
	
	/**
	 * 
	 */
	public function getAll()
	{
		$this->response($this->NationModel->getAll());
	}
}