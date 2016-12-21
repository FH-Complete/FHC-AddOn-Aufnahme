<?php

/**
 * 
 */
class Kontakt_model extends REST_Model
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
	public function getOnlyKontaktByPersonId()
	{
		return $this->load('person/Kontakt/OnlyKontaktByPersonId', array('person_id' => $this->getPersonId()), 'Kontakt.getKontakt');
	}
	
	/**
	 * 
	 */
	public function saveKontakt($parameters)
	{
		return $this->save('person/Kontakt/Kontakt', $parameters, 'Kontakt.getKontakt');
	}
}