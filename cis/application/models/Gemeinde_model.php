<?php

class Gemeinde_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function getGemeinde()
    {
	if ($restquery = $this->rest->get('codex/gemeinde/gemeinde'))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
