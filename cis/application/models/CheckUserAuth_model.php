<?php

/**
 * 
 */
class CheckUserAuth_model extends REST_Model
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
	public function checkByUsernamePassword($username, $password)
	{
		$checkUserAuth = false;
		
		$result = $this->load(
			'checkUserAuth/CheckByUsernamePassword',
			array('username' => $username, 'password' => $password)
		);
		
		if (isSuccess($result))
		{
			if ($result->retval === true)
			{	
				$checkUserAuth = true;
			}
		}
		
		return $checkUserAuth;
	}
}