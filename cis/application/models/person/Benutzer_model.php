<?php

/**
 * 
 */
class Benutzer_model extends REST_Model
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
	public function getBenutzer($uid)
	{
		return $this->load('person/Benutzer/Benutzer', array('uid' => $uid), 'Benutzer.getBenutzer');
	}
}