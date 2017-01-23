<?php

/**
 * 
 */
class Studiengangstyp_model extends REST_Model
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
	public function getStudiengangstyp($typ)
	{
		return $this->loadOne('organisation/Studiengangstyp/Studiengangstyp', array('typ' => $typ));
	}
}