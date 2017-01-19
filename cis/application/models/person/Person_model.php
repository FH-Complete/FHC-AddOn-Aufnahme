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
		return $this->loadOne(
			'person/Person/CheckBewerbung',
			array(
				'email' => $email,
				'studiensemester_kurzbz' => $studiensemester_kurzbz
			),
			null,
			Parent::AUTH_NOT_REQUIRED
		);
	}
	
	/**
	 * 
	 */
	public function savePerson($parameters, $authNotRequired = false)
	{
		return $this->save('person/Person/Person', $parameters, 'Person.getPerson', $authNotRequired);
	}
}