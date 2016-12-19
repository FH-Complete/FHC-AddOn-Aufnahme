<?php

/**
 * 
 */
class Oe_model extends REST_Model
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
	public function getOrganisationseinheit($oe_kurzbz)
	{
		return $this->load('organisation/Organisationseinheit/Organisationseinheit', array('oe_kurzbz' => $oe_kurzbz));
	}
}