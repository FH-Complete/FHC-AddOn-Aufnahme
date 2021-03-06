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
	public function getStudiengang($studiengang_kz, $forceApiCall = false)
	{
        if($forceApiCall)
        {
            unset($this->session->userdata['Studiengang.getStudiengang']);
        }

		return $this->loadOne(
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
	    if(($this->config->item('root_oe_stg') !== null) &&  ($this->config->item('root_oe_stg') !== ''))
        {
            return $this->load(
                'organisation/Studiengang/StudiengangBewerbung',
                array(
                    'oe_kurzbz' => $this->config->item('root_oe_stg')
                ),
                'Studiengang.getStudiengangBewerbung');
        }
        else
        {
            return $this->load(
                'organisation/Studiengang/StudiengangBewerbung',
                array(),
                'Studiengang.getStudiengangBewerbung');
        }

	}
	
	/**
	 * 
	 */
	public function getAppliedStudiengang($studiensemester_kurzbz, $titel, $status_kurzbz, $forceApiCall = false)
	{
	    if($forceApiCall)
        {
            unset($this->session->userdata['Studiengang.getAppliedStudiengang']);
        }

		return $this->load(
			'organisation/Studiengang/AppliedStudiengang',
			array(
				'person_id' => $this->getPersonId(),
				'studiensemester_kurzbz' => $studiensemester_kurzbz,
				'titel' => $titel,
				'status_kurzbz' => $status_kurzbz
			),
			'Studiengang.getAppliedStudiengang');
	}

    public function getAppliedStudiengangFromNow($titel, $forceApiCall = false)
    {
        if($forceApiCall)
        {
            unset($this->session->userdata['Studiengang.getAppliedStudiengang']);
        }

        if(($this->config->item('root_oe_stg') !== null) &&  ($this->config->item('root_oe_stg') !== ''))
        {
            return $this->load(
                'organisation/Studiengang/AppliedStudiengangFromNowOe',
                array(
                    'person_id' => $this->getPersonId(),
                    'titel' => $titel,
                    'oe_kurzbz' => $this->config->item('root_oe_stg')
                ),
                'Studiengang.getAppliedStudiengang');
        }
        else
        {
            return $this->load(
                'organisation/Studiengang/AppliedStudiengangFromNow',
                array(
                    'person_id' => $this->getPersonId(),
                    'titel' => $titel
                ),
                'Studiengang.getAppliedStudiengang');
        }
    }
}