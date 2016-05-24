<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Organisationsform_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getOrgform($orgform_kurzbz)
    {
        if ($restquery = $this->rest->get('codex/orgform/orgform', array("orgform_kurzbz"=>$orgform_kurzbz)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    function getAll()
    {
        if ($restquery = $this->rest->get('codex/orgform/all'))
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