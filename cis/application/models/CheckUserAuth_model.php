<?php

/**
 * 
 */
class Message_model extends REST_Model
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
	public function checkByUsernamePassword($parameters)
	{
		return $this->load('checkUserAuth/CheckByUsernamePassword', $parameters);
	}
}