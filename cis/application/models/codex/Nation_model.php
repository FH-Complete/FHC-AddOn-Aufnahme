<?php

/**
 * 
 */
class Nation_model extends REST_Model
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
		return $this->load('codex/Nation/All', null, 'Nation.getAll');
	}
}