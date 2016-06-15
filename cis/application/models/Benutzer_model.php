<?php

class Benutzer_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getBenutzer($uid)
    {
	if ($restquery = $this->rest->get('person/benutzer/benutzer', array("uid" => $uid, 'json')))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
