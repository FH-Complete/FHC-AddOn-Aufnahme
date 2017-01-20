<?php

/**
 * 
 */
class Gemeinde_model extends REST_Model
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
	public function getGemeinde()
	{
		return $this->load('codex/Gemeinde/Gemeinde', null, 'Gemeinde.getGemeinde');
	}
	
	/**
	 * 
	 */
	public function getGemeindeByPlz($plz, $authNotRequired = true)
	{
		return $this->load('codex/Gemeinde/GemeindeByPlz', array('plz' => $plz), null, $authNotRequired);
	}
}