<?php

/**
 * 
 */
class Adresse_model extends REST_Model
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
	public function getAdresse()
	{
		return $this->load('person/Adresse/Adresse', array('person_id' => $this->getPersonId()), 'Adresse.getAdresse');
	}
	
	/**
	 * 
	 */
	public function saveAdresse($parameters)
	{
		return $this->save('person/Adresse/Adresse', $parameters, 'Adresse.getAdresse');
	}
}