<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studienplan_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function getStudienplan($studienplan_id)
    {
        if ($restquery = $this->rest->get('organisation/studienplan/studienplan', array("studienplan_id"=>$studienplan_id)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }

    function getStudienplaene($studiengang_kz)
    {
        if ($restquery = $this->rest->get('organisation/studienplan/studienplaene', array("studiengang_kz"=>$studiengang_kz)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    function getStudienplaeneFromSem($data)
    {
        if ($restquery = $this->rest->get('organisation/studienplan/StudienplaeneFromSem', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}

/* End of file Studiengang_model.php */
/* Location: ./application/models/Studiengang_model.php */