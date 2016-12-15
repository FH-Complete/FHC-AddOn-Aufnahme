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
	public function getDokument($dokumenttyp_kurzbz)
	{
		return $this->load('crm/Dokument/Dokument', array('dokumenttyp_kurzbz' => $dokumenttyp_kurzbz));
	}
}