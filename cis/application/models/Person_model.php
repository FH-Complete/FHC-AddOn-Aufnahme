<?php

class Person_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function getPersonen($person_id = NULL)
    {
	if ($restquery = $this->rest->get('Person/person', array("person_id" => $person_id, 'json')))
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
	
	if ($restquery = $this->rest->get('Person/person', $data))
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
	if ($restquery = $this->rest->post('Person/person', $data))
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
	if ($restquery = $this->rest->post('Person/personUpdate', $data))
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
	if ($restquery = $this->rest->get('Person/checkBewerbung', $data))
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
