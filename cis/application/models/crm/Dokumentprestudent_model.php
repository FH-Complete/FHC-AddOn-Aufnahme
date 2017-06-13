<?php

/**
 * 
 */
class Dokumentprestudent_model extends REST_Model
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
	public function setAccepted($prestudent_id, $studiengang_kz)
	{

        unset($this->session->userdata{"aktenAccepted:".$this->getPersonId()});

		return $this->save(
			'crm/Dokumentprestudent/SetAccepted',
			array('prestudent_id' => $prestudent_id, 'studiengang_kz' => $studiengang_kz)
		);
	}
	
	/**
	 * 
	 */
	public function setAcceptedDocuments($prestudent_id, $studiengang_kz, $dokument_kurzbz_array)
	{
		return $this->save(
			'crm/Dokumentprestudent/SetAcceptedDocuments',
			array(
				'prestudent_id' => $prestudent_id,
				'studiengang_kz' => $studiengang_kz,
				'dokument_kurzbz' => $dokument_kurzbz_array
			)
		);
	}
}