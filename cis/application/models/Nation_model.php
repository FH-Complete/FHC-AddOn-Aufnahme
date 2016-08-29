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
	if(isset($this->session->userdata()["nationen"]))
	{
	    $this->result= $this->session->userdata()["nationen"];
	    return true;
	}
	else
	{	    
	    if ($restquery = $this->rest->get('codex/nation/all'))
	    {
		$this->result = $restquery;
		$this->session->set_userdata("nationen", $this->result);
		return true;
	    }
	    else
		return false;
	}
    }
}