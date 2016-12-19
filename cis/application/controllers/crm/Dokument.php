<?php

/**
 * 
 */
class Dokument extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model DokumentModel
		$this->load->model('crm/Dokument_model', 'DokumentModel');
	}
	
	/**
	 * 
	 */
	public function getDokument($dokumenttyp_kurzbz)
	{
		$this->response($this->DokumentModel->getDokument($dokumenttyp_kurzbz));
	}
}