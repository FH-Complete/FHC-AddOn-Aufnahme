<?php

class Bundesland_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function getBundeslaender()
    {
	if ($restquery = $this->rest->get('codex/bundesland/all'))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}