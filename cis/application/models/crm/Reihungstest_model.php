<?php

/**
 * 
 */
class Reihungstest_model extends REST_Model
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
	public function getReihungstest($reihungstest_id)
	{
		return $this->loadOne('crm/Reihungstest/Reihungstest', array('reihungstest_id' => $reihungstest_id));
	}
	
	/**
	 * 
	 */
	public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz = null, $available = null)
	{
		return $this->load(
			'crm/Reihungstest/ByStudiengangStudiensemester',
			array(
				'studiengang_kz' => $studiengang_kz,
				'studiensemester_kurzbz' => $studiensemester_kurzbz,
				'available' => $available
			)
		);
	}
	
	/**
	 * 
	 */
	public function getReihungstestByPersonId($available = null)
	{
		return $this->load(
			'crm/Reihungstest/ReihungstestByPersonId',
			array('person_id' => $this->getPersonId(), 'available' => $available)
		);
	}
	
	/**
	 * 
	 */
	public function getAvailableReihungstestByPersonId()
	{
		return $this->load('crm/Reihungstest/AvailableReihungstestByPersonId', array('person_id' => $this->getPersonId()));
	}
}