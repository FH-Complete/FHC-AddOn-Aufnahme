<?php

/**
 * 
 */
class Bundesland extends JSON_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model BundeslandModel
		$this->load->model('codex/Bundesland_model', 'BundeslandModel');
	}
	
	/**
	 * 
	 */
	public function getAll()
	{
		$this->response($this->BundeslandModel->getAll());
	}
}