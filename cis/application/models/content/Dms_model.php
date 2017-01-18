<?php

/**
 * 
 */
class Dms_model extends REST_Model
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
	public function getDms($dms_id)
	{
		return $this->loadOne('content/Dms/Dms', array('dms_id' => $dms_id));
	}
	
	/**
	 * 
	 */
	public function getAktenAcceptedDms($dokument_kurzbz = null)
	{
		return $this->load(
			'content/Dms/AktenAcceptedDms',
			array('person_id' => $this->getPersonId(), 'dokument_kurzbz' => $dokument_kurzbz)
		);
	}
	
	/**
	 * 
	 */
	public function saveDms($parameters)
	{
		return $this->save('content/Dms/Dms', $parameters);
	}
	
	/**
	 * 
	 */
	public function deleteDms($dms_id)
	{
		return $this->save('content/Dms/Deldms', array(
		    'person_id' => $this->getPersonId(),
		    'dms_id' => $dms_id
        ));
	}
}