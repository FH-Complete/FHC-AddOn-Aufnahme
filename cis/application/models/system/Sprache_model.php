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
		return $this->load('system/Sprache/Sprache', $sprache, 'Sprache.getSprache');
	}
}