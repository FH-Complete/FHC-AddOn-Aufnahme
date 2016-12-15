<?php

/**
 * 
 */
class Organisationsform extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model OrganisationsformModel
		$this->load->model('codex/Organisationsform_model', 'OrganisationsformModel');
	}
	
	/**
	 * 
	 */
	public function getOrgform($orgform_kurzbz)
	{
		$this->response($this->OrganisationsformModel->getOrgform($orgform_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getAll()
	{
		$this->response($this->OrganisationsformModel->getAll());
	}
}