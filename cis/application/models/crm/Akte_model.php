<?php

/**
 * 
 */
class Akte_model extends REST_Model
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
	public function getAkten($person_id, $dokumenttyp_kurzbz = null)
	{
		return $this->load('crm/Akte/Akten', array('person_id' => $person_id, 'dokumenttyp_kurzbz' => $dokumenttyp_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getAktenAccepted($person_id, $dokumenttyp_kurzbz = null)
	{
		return $this->load(
			'crm/Akte/Aktenaccepted',
			array(
				'person_id' => $person_id,
				'dokumenttyp_kurzbz' => $dokumenttyp_kurzbz
			)
		);
	}
	
	/**
	 * 
	 */
	public function saveAkte($parameters)
	{
		return $this->save('crm/Akte/Akte', $parameters);
	}
}