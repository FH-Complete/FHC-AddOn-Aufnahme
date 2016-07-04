<?php

class Akte_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
	//$this->load->database();
    }

    public function saveAkte($data)
    {
	if ($restquery = $this->rest->post('crm/akte/akte', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function getAkten($person_id = NULL, $dokumenttyp_kurzbz = null)
    {
        //TODO change akte id
	if ($restquery = $this->rest->get('crm/akte/akten', array("person_id" => $person_id, "dokumenttyp_kurzbz"=> $dokumenttyp_kurzbz)))
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