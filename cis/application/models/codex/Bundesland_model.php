<?php

/**
 * 
 */
class Bundesland_model extends REST_Model
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
	public function getAll()
	{
		return $this->load('codex/Bundesland/All', null, 'Bundesland.getBundeslaender');
	}
}