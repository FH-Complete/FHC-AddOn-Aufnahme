<?php

/**
 * 
 */
class Oe extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model OeModel
		$this->load->model('organisation/Oe_model', 'OeModel');
	}
	
	/**
	 * 
	 */
	public function getOrganisationseinheit($oe_kurzbz)
	{
		$this->response($this->OeModel->getOrganisationseinheit($oe_kurzbz));
	}
}