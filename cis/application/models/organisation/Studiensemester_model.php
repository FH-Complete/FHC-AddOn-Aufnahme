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
		return $this->loadOne(
			'organisation/Studiensemester/Nextstudiensemester',
			array('art' => $art),
			'Studiensemester.getNextStudiensemester'
		);
	}

    /**
     *
     */
    public function getAktStudiensemester()
    {
        return $this->loadOne(
            'organisation/Studiensemester/akt',
            array(),
            'Studiensemester.getAktStudiensemester'
        );
    }

    /**
     *
     */
    public function getStudiensemester($studiensemester_kurzbz, $forceApiCall=false)
    {
        if($forceApiCall)
        {
            unset($this->session->userdata['Studiensemester.getAktStudiensemester']);
        }

        return $this->loadOne(
            'organisation/Studiensemester/studiensemester',
            array(
                'studiensemester_kurzbz' => $studiensemester_kurzbz
            ),
            'Studiensemester.getAktStudiensemester'
        );
    }
}