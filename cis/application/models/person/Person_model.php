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
	public function getPerson($code = null, $email = null, $authNotRequired = false)
	{
		return $this->loadOne(
			'person/Person/Person',
			array(
				'person_id' => $this->getPersonId(),
				'code' => $code,
				'email' => $email
			),
			'Person.getPerson',
			$authNotRequired
		);
	}
	
	/**
	 * 
	 */
	public function getPersonByPersonId($person_id)
	{
		return $this->loadOne(
			'person/Person/Person',
			array(
				'person_id' => $person_id
			),
			'Person.getPerson',
			Parent::AUTH_NOT_REQUIRED
		);
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
	
	/**
	 * 
	 */
	public function savePerson($parameters)
	{
		return $this->save('person/Person/Person', $parameters, 'Person.getPerson');
	}
}