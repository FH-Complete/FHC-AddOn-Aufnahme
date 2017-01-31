<?php

/**
 * 
 */
class Benutzer_model extends REST_Model
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
	public function getBenutzer($uid, $authNotRequired = false, $forceApiCall = false)
	{
        if($forceApiCall)
        {
            unset($this->session->userdata{'Benutzer.getBenutzer'});
        }

		return $this->loadOne(
			'person/Benutzer/Benutzer',
			array(
				'uid' => $uid
			),
			'Benutzer.getBenutzer',
			$authNotRequired
		);
	}
}