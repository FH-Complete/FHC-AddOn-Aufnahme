<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studiengang_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getAll()
    {
        if ($restquery = $this->rest->get('organisation/studiengang/allForBewerbung'))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    function getStudiengang($stgkz)
    {
        if ($restquery = $this->rest->get('organisation/studiengang/studiengang', array("studiengang_kz"=>$stgkz)))
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