<?php

class Kontakt_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function saveKontakt($data)
    {
	if ($restquery = $this->rest->post('person/kontakt/kontakt', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function getKontakt($person_id = NULL)
    {
	if ($restquery = $this->rest->get('person/kontakt/kontaktByPersonId', array("person_id" => $person_id)))
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
