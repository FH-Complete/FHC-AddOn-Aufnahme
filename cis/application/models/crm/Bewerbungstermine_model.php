<?php

/**
 * 
 */
class Bewerbungstermine_model extends REST_Model
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
	public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz)
	{
		return $this->load(
			'crm/Bewerbungstermine/ByStudiengangStudiensemester',
			array('studiengang_kz' => $studiengang_kz, 'studiensemester_kurzbz' => $studiensemester_kurzbz),
            "getByStudiengangStudiensemester:".$studiengang_kz.":".$studiensemester_kurzbz
		);
	}
	
	/**
	 * 
	 */
	public function getCurrent()
	{
		return $this->load('crm/Bewerbungstermine/Current');
	}
	
	/**
	 * 
	 */
	public function getByStudienplan($studienplan_id)
	{
		return $this->load('crm/Bewerbungstermine/ByStudienplan', array('studienplan_id' => $studienplan_id));
	}
}