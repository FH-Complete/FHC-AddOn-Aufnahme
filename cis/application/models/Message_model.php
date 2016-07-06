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
    
    public function sendMessageVorlage($sender_id, $receiver_id, $vorlage_kurzbz, $oe_kurzbz, $data, $orgform_kurzbz = null)
    {
	$message = array(
	    "sender_id" => $sender_id,
	    "receiver_id" => $receiver_id,
	    "vorlage_kurzbz" => $vorlage_kurzbz,
	    "oe_kurzbz" => $oe_kurzbz,
	    "data" => $data,
	    "orgform_kurzbz" => $orgform_kurzbz,
	    "relationmessage_id" => null
	);
	
	if ($restquery = $this->rest->post('system/message/messageVorlage', $message))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    public function changeMessageStatus($person_id, $message_id, $status)
    {
	if ($restquery = $this->rest->post('system/message/changeStatus', array("person_id" => $person_id, "message_id"=>$message_id, "status"=>$status)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}
