<?php

class Nation_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function getNationen()
    {
	if ($restquery = $this->rest->get('codex/nation/all'))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
