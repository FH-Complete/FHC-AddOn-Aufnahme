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
	if ($restquery = $this->rest->post('Kontakt/kontakt', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function getKontaktPerson($person_id)
    {
	if ($restquery = $this->rest->get('Kontakt/kontaktPerson', array("person_id" => $person_id)))
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
