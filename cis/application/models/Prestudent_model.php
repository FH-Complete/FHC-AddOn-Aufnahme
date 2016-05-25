<?php

class Prestudent_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getPrestudent($data)
    {
        if(isset($data["prestudent_id"]))
        {
            if ($restquery = $this->rest->get('crm/prestudent/prestudent', array("prestudent_id" => $data["prestudent_id"], 'json')))
            {
                $this->result = $restquery;
                return true;
            }
        }
        elseif(isset($data["person_id"]))
        {
            if ($restquery = $this->rest->get('crm/prestudent/prestudentByPersonId', array("person_id" => $data["person_id"], 'json')))
            {
                $this->result = $restquery;
                return true;
            }
        }
        
        return false;
    }

    public function savePrestudent($data)
    {
        
	if ($restquery = $this->rest->post('crm/prestudent/prestudent', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
            var_dump($restquery);
	    return false;
	}
    }
}
