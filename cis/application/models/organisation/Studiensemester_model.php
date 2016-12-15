<?php

/**
 * 
 */
class Studiensemester_model extends REST_Model
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
	public function getNextStudiensemester($art = null)
	{
		return $this->load(
			'organisation/Studiensemester/Nextstudiensemester',
			array('art' => $art),
			'Studiensemester.getNextStudiensemester'
		);
	}
}