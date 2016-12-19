<?php

/**
 * 
 */
class Organisationsform_model extends REST_Model
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
	public function getOrgform($orgform_kurzbz)
	{
		return $this->load('codex/Orgform/Orgform', array('orgform_kurzbz' => $orgform_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getAll()
	{
		return $this->load('codex/Orgform/All', null, 'Organisationsform.getAll');
	}
}