<?php

class Person_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getPersonen($person_id = NULL)
    {
	if ($restquery = $this->rest->get('person/person/person', array("person_id" => $person_id, 'json')))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }

    public function getPersonFromCode($code, $email = null)
    {
	$data = array(
	    'code' => $code,
	    'email' => $email
	);
	
	if ($restquery = $this->rest->post('Person/personFromCode', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }

    public function savePerson($data)
    {
	if ($restquery = $this->rest->post('person/person/person', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function updatePerson($data)
    {
	if ($restquery = $this->rest->post('person/person/person', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{   
	    return false;
	}
    }
    
    public function checkBewerbung($data)
    {
	if ($restquery = $this->rest->get('person/person/CheckBewerbung', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function checkZugangscodePerson($code)
    {
	if ($restquery = $this->rest->get('Person/checkZugangscodePerson', $code))
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
