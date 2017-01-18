<?php

/**
 * 
 */
class Sprache_model extends REST_Model
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
	public function getSprache($sprache = null)
	{
		return $this->loadOne('system/Sprache/Sprache', array('sprache' => $sprache), 'Sprache.getSprache');
	}
}