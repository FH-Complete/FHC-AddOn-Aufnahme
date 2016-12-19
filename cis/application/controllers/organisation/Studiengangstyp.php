<?php

/**
 * 
 */
class Studiengangstyp extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model StudiengangstypModel
		$this->load->model('organisation/Studiengangstyp_model', 'StudiengangstypModel');
	}
	
	/**
	 * 
	 */
	public function getStudiengangstyp($typ)
	{
		$this->response($this->StudiengangstypModel->getStudiengangstyp($typ));
	}
}