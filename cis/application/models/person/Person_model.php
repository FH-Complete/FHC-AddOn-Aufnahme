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
	public function getPerson($code = null, $email = null)
	{
		$person = $this->load('person/Person/Person', array(
			'person_id' => $this->getPersonId(),
			'code' => $code,
			'email' => $email
		));
		
		return $person[0];
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