<?php

class Adresse_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function saveAdresse($data)
    {
	if ($restquery = $this->rest->post('person/adresse/adresse', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function getAdresse($person_id = NULL)
    {
	if ($restquery = $this->rest->get('person/adresse/adresse', array("person_id" => $person_id)))
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
