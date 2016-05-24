<?php

class Studiensemester_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function getNextStudiensemester($art = null)
    {
	if ($restquery = $this->rest->get('organisation/studiensemester/nextstudiensemester', $art))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
