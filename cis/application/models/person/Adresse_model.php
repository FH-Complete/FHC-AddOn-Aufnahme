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
		$adresses = $this->load('person/Adresse/Adresse', array('person_id' => $this->getPersonId()), 'Adresse.getAdresse');
		$adressesArray = array();
		
		if (isSuccess($adresses))
		{
			foreach ($adresses->retval as $adress)
			{
				if ($adress->heimatadresse == true)
				{
					$adressesArray[] = $adress;
				}
			}
			
			if (count($adressesArray) > 0)
			{
				$this->storeSession('Adresse.getZustelladresse', $adressesArray);
			}
		}
		
		return success($adressesArray);
	}
	
	/**
	 * 
	 */
	public function getZustelladresse()
	{
		$adresses = $this->load('person/Adresse/Adresse', array('person_id' => $this->getPersonId()), 'Adresse.getZustelladresse');
		$adressesArray = array();
		
		if (isSuccess($adresses))
		{
			foreach ($adresses->retval as $adress)
			{
				if (($adress->heimatadresse == false) && ($adress->zustelladresse == true))
				{
					$adressesArray[] = $adress;
				}
			}
			
			if (count($adressesArray) > 0)
			{
				$this->storeSession('Adresse.getZustelladresse', $adressesArray);
			}
		}
		
		return success($adressesArray);
	}
	
	/**
	 * 
	 */
	public function saveAdresse($parameters)
	{
		return $this->save('person/Adresse/Adresse', $parameters, 'Adresse.getAdresse');
	}
}