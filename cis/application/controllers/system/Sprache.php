<?php

/**
 * 
 */
class Sprache extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model SpracheModel
		$this->load->model('system/Sprache_model', 'SpracheModel');
	}
	
	/**
	 * 
	 */
	public function getSprache($sprache = null)
	{
		$this->response($this->SpracheModel->getSprache($sprache));
	}
}