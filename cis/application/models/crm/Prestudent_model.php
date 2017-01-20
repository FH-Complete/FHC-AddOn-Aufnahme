<?php

/**
 * 
 */
class Prestudent_model extends REST_Model
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
	public function getPrestudent($prestudent_id)
	{
		return $this->load('crm/Prestudent/Prestudent', array('prestudent_id' => $prestudent_id));
	}
	
	/**
	 * 
	 */
	public function getPrestudentByPersonId($forceApiCall = false)
	{
	    if($forceApiCall)
        {
            unset($this->session->userdata['prestudent']);
        }
		return $this->load('crm/Prestudent/prestudentByPersonId', array('person_id' => $this->getPersonId()), 'prestudent');
	}
	
	/**
	 * 
	 */
	public function getSpecialization($prestudent_id, $titel = "aufnahme/spezialisierung")
	{
		return $this->loadOne('crm/Prestudent/Specialization', array('prestudent_id' => $prestudent_id, 'titel' => $titel), 'Prestudent.Specialization');
	}
	
	/**
	 * 
	 */
	public function getLastStatuses($person_id, $studiensemester_kurzbz = null, $studiengang_kz = null, $status_kurzbz = null, $forceApiCall = false)
	{
	    if($forceApiCall)
        {
            unset($this->session->userdata['prestudent']);
        }
		return $this->load('crm/Prestudent/LastStatuses', array(
			'person_id' => $person_id,
			'studiensemester_kurzbz' => $studiensemester_kurzbz,
			'studiengang_kz' => $studiengang_kz,
			'status_kurzbz' => $status_kurzbz
		),
        'prestudent'
        );
	}
	
	/**
	 * 
	 */
	public function savePrestudent($parameters)
	{
	    unset($parameters['studiensemester_kurzbz']);
        unset($parameters['ausbildungssemester']);
        unset($parameters['datum']);
        unset($parameters['kurzbz']);
        unset($parameters['studienplan_id']);
        unset($parameters['bestaetigtam']);
        unset($parameters['bestaetigtvon']);
        unset($parameters['fgm']);
        unset($parameters['faktiv']);
        unset($parameters['bewerbung_abgeschicktamum']);
        unset($parameters['rt_stufe']);
        unset($parameters['beschreibung']);
        unset($parameters['bezeichnung_mehrsprachig']);
        unset($parameters['status_kurzbz']);
        unset($parameters['orgform_kurzbz']);
        unset($parameters['spezialisierung']);

		return $this->save('crm/Prestudent/Prestudent', $parameters, 'prestudent');
	}
	
	/**
	 * 
	 */
	public function saveSpecialization($parameters)
	{
	    $parameters['titel'] = "aufnahme/spezialisierung";
		return $this->save('crm/Prestudent/Specialization', $parameters, 'Prestudent.Specialization');
	}
	
	/**
	 * 
	 */
	public function registerToReihungstest($parameters)
	{
		return $this->save('crm/Prestudent/AddReihungstest', $parameters);
	}
	
	/**
	 * 
	 */
	public function removePrestudent($parameters)
	{
		return $this->delete('crm/Prestudent/Prestudent', $parameters, 'prestudent');
	}
	
	/**
	 * 
	 */
	public function removeSpecialization($parameters)
	{
		return $this->save('crm/Prestudent/RmSpecialization', $parameters, 'Prestudent.Specialization');
	}
	
	/**
	 * 
	 */
	public function removeRegistrationToReihungstest($parameters)
	{
		return $this->delete('crm/Prestudent/DelReihungstest', $parameters);
	}
}