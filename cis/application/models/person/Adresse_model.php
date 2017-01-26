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
		$adresse = null;
		
		if (isSuccess($adresses))
		{
			foreach ($adresses->retval as $adress)
			{
				if ($adress->heimatadresse == true)
				{
					$adresse = $adress;
					break;
				}
			}
			
			if ($adresse !== null)
			{
				$this->storeSession('Adresse.getAdresse', $adresse);
			}
		}
		else if (is_object($adresses))
		{
			$adresse = $adresses;
		}
		
		return success($adresse);
	}
	
	/**
	 * 
	 */
	public function getZustelladresse()
	{
		$adresses = $this->load('person/Adresse/Adresse', array('person_id' => $this->getPersonId()), 'Adresse.getZustelladresse');
		$adresse = null;
		
		if (isSuccess($adresses))
		{
			foreach ($adresses->retval as $adress)
			{
				if ($adress->heimatadresse == false && $adress->zustelladresse == true)
				{
					$adresse = $adress;
					break;
				}
			}
			
			if ($adresse !== null)
			{
				$this->storeSession('Adresse.getZustelladresse', $adresse);
			}
		}
		else if (is_object($adresses))
		{
			$adresse = $adresses;
		}
		
		return success($adresse);
	}
	
	/**
	 * 
	 */
	public function saveAdresse($parameters, $authNotRequired = false)
	{
		return $this->save('person/Adresse/Adresse', $parameters, 'Adresse.getAdresse', $authNotRequired);
	}

    public function saveZustellAdresse($parameters, $authNotRequired = false)
    {
        return $this->save('person/Adresse/Adresse', $parameters, 'Adresse.getZustelladresse', $authNotRequired);
    }
}