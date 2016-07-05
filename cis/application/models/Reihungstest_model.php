<?php

class Reihungstest_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz)
    {
	if ($restquery = $this->rest->get('crm/reihungstest/ByStudiengangStudiensemester', array("studiengang_kz" => $studiengang_kz, "studiensemester_kurzbz"=> $studiensemester_kurzbz,'json')))
	{
	    $this->result = $restquery;
	    return true;
	}
    }
    
    public function getReihungstestByPersonID($person_id)
    {
	if ($restquery = $this->rest->get('crm/reihungstest/reihungstestByPersonId', array("person_id" => $person_id,'json')))
	{
	    $this->result = $restquery;
	    return true;
	}
    }
    
    
}
