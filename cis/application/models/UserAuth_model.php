<?php

class UserAuth_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function checkByUsernamePassword($data)
    {
	if ($restquery = $this->rest->get('checkUserAuth/CheckByUsernamePassword', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }

}
