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
		$result = $this->load(
			'crm/Reihungstest/ReihungstestByPersonId',
			array('person_id' => $this->getPersonId(), 'available' => $available)
		);

        $anmeldungen = array();
        foreach ($result->retval as $anmeldung)
        {
            if (!isset($anmeldungen[$anmeldung->studiengang_kz]))
            {
                $anmeldungen[$anmeldung->studiengang_kz] = array();
            }
            array_push($anmeldungen[$anmeldung->studiengang_kz], $anmeldung);
        }
        $result->retval = $anmeldungen;

		return $result;
	}
}