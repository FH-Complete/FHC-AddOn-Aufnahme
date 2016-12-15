<?php

/**
 * 
 */
class Studiengang_model extends REST_Model
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
	public function getAllForBewerbung()
	{
		return $this->load('organisation/Studiengang/AllForBewerbung');
	}
	
	/**
	 * 
	 */
	public function getStudiengang($studiengang_kz)
	{
		return $this->load(
			'organisation/Studiengang/Studiengang',
			array('studiengang_kz' => $studiengang_kz), 'Studiengang.getStudiengang'
		);
	}
	
	/**
	 * 
	 */
	public function getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester)
	{
		return $this->load(
			'organisation/Studiengang/StudiengangStudienplan',
			array('studiensemester_kurzbz' => $studiensemester_kurzbz, 'ausbildungssemester' => $ausbildungssemester),
			'Studiengang.getStudiengangStudienplan'
		);
	}
	
	/**
	 * 
	 */
	public function getStudiengangBewerbung()
	{
		return $this->load('organisation/Studiengang/StudiengangBewerbung', null, 'Studiengang.getStudiengangBewerbung');
	}
}