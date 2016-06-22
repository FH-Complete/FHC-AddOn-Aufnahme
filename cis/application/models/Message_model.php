<?php

class Message_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getMessagesByPersonId($person_id)
    {
	if ($restquery = $this->rest->get('system/message/MessagesByPersonId', array("person_id"=>$person_id, "all"=>true)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    public function sendMessage($data)
    {
	if ($restquery = $this->rest->post('system/message/message', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
