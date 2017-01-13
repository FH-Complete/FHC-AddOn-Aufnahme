<?php

/**
 * 
 */
class Dokument_model extends REST_Model
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 */
	public function getDokument($dokument_kurzbz)
	{
		return $this->load('crm/Dokument/Dokument', array('dokument_kurzbz' => $dokument_kurzbz), 'Dokument.getDokument.'.$dokument_kurzbz);
	}
}