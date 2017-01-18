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
		$kontakts = $this->load('person/Kontakt/OnlyKontaktByPersonId', array('person_id' => $this->getPersonId()), 'Kontakt.getKontakt');
		$kontaktsArray = array();
		
		if (isSuccess($kontakts))
		{
			foreach ($kontakts->retval as $kontakt)
			{
				$kontaktsArray[$kontakt->kontakttyp] = $kontakt;
			}
			
			if (count($kontaktsArray) > 0)
			{
				$this->storeSession('Kontakt.getKontakt', $kontaktsArray);
			}
		}
		
		return success($kontaktsArray);
	}
	
	/**
	 * 
	 */
	public function saveKontakt($parameters, $authNotRequired = false)
	{
		return $this->save('person/Kontakt/Kontakt', $parameters, 'Kontakt.getKontakt', $authNotRequired);
	}
}