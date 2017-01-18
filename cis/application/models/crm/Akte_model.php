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
	public function getAkten($dokumenttyp_kurzbz = null)
	{
		return $this->load('crm/Akte/Akten', array('person_id' => $this->getPersonId(), 'dokumenttyp_kurzbz' => $dokumenttyp_kurzbz));
	}
	
	/**
	 * 
	 */
	public function getAktenAccepted($dokumenttyp_kurzbz = null)
	{
	    $result = $this->load(
			'crm/Akte/Aktenaccepted',
			array(
				'person_id' => $this->getPersonId(),
				'dokumenttyp_kurzbz' => $dokumenttyp_kurzbz
			)
		);

	    $dokumente = array();
        foreach($result->retval as $akte)
        {
            $dokumente[$akte->dokument_kurzbz] = $akte;
        }

        $result->retval = $dokumente;

        return $result;
	}
	
	/**
	 * 
	 */
	public function saveAkte($parameters)
	{
		return $this->save('crm/Akte/Akte', $parameters);
	}
}