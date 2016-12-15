<?php

/**
 * 
 */
class Person_model extends REST_Model
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
	public function getPerson($person_id = null, $code = null, $email = null)
	{
		return $this->load('person/Person/Person', array(
			'person_id' => $person_id,
			'code' => $code,
			'email' => $email
		));
	}
	
	/**
	 * 
	 */
	public function checkBewerbung($email, $studiensemester_kurzbz = null)
	{
		return $this->load('person/Person/CheckBewerbung', array(
			'email' => $email,
			'studiensemester_kurzbz' => $studiensemester_kurzbz
		));
	}
}