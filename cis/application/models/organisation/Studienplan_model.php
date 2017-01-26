<?php

/**
 * 
 */
class Studienplan_model extends REST_Model
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
	public function getStudienplan($studienplan_id)
	{
		return $this->loadOne('organisation/Studienplan/Studienplan', array('studienplan_id' => $studienplan_id));
	}
	
	/**
	 * 
	 */
	public function getStudienplaene($studiengang_kz)
	{
		return $this->load('organisation/Studienplan/Studienplaene', array('studiengang_kz' => $studiengang_kz));
	}
	
	/**
	 * 
	 */
	public function getStudienplaeneFromSem($studiengang_kz, $studiensemester_kurzbz, $ausbildungssemester = null, $orgform_kurzbz = null)
	{
		return $this->load(
			'organisation/Studienplan/StudienplaeneFromSem',
			array(
				'studiengang_kz' => $studiengang_kz,
				'studiensemester_kurzbz' => $studiensemester_kurzbz,
				'ausbildungssemester' => $ausbildungssemester,
				'orgform_kurzbz' => $orgform_kurzbz
			)
		);
	}
}