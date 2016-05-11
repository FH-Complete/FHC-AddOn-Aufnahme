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
        if ($restquery = $this->rest->get('lehre/studiengang_api/allForBewerbung'))
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