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
	public function getPerson($code = null, $email = null, $authNotRequired = false, $forceApiCall = false)
	{
        if($forceApiCall)
        {
            unset($this->session->userdata['Person.getPerson']);
        }

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
	public function getPersonByPersonId($person_id, $forceApiCall = false)
	{
	    if($forceApiCall)
        {
            unset($this->session->userdata{'Person.getPerson'});
        }

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
	public function checkBewerbung($email, $studiensemester_kurzbz = null, $forceApiCall = false)
	{
	    if($forceApiCall)
        {
            unset($this->session->userdata{'Person.getPerson'});
        }

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
	    unset($parameters["kontakt_id"]);
        unset($parameters["kontakttyp"]);
        unset($parameters["kontakt"]);
        unset($parameters["zustellung"]);
        unset($parameters["standort_id"]);
        unset($parameters["bundesland_bezeichnung"]);
        unset($parameters["geburtsnation_text"]);
		$result = $this->save('person/Person/Person', $parameters, 'Person.getPerson', $authNotRequired);

		if(isSuccess($result))
        {
            return $this->getPersonByPersonId($result->retval);
        }
        else
        {
            return $result;
        }

	}
}
