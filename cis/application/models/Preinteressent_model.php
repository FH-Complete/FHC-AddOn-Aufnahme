<?php

class Preinteressent_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getPreinteressent($person_id = NULL)
    {
	if ($restquery = $this->rest->get('crm/preinteressent/preinteressent', array("person_id" => $person_id, 'json')))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }

    public function savePreinteressent($data)
    {
	if ($restquery = $this->rest->post('crm/preinteressent/preinteressent', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
}
