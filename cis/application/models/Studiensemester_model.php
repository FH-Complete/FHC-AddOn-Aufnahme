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
	if(isset($this->session->userdata()["studiensemester"]))
	{
	    $this->result = $this->session->userdata()["studiensemester"];
	    return true;
	}
	else
	{
	    if ($restquery = $this->rest->get('organisation/studiensemester/nextstudiensemester', $art))
	    {
		$this->result = $restquery;
		$this->session->set_userdata("studiensemester", $this->result);
		return true;
	    }
	    else
		return false;
	}
    }
}
