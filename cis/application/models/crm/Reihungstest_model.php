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
		return $this->load('crm/Reihungstest/Reihungstest', array('reihungstest_id' => $reihungstest_id));
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
				'studiensemester_kurzbz' => $studiengang_kz,
				'available' => $studiengang_kz
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
}