<?php

/**
 * 
 */
class Message_model extends REST_Model
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 */
	public function getMessagesByPersonId($person_id)
	{
		return $this->load('system/Message/MessagesByPersonId', array('person_id' => $person_id));
	}
	
	/**
	 * 
	 */
	public function getSentMessagesByPersonId($person_id)
	{
		return $this->load('system/Message/SentMessagesByPerson', array('person_id' => $person_id));
	}
	
	/**
	 * 
	 */
	public function sendMessage($parameters)
	{
		return $this->save('system/Message/Message', $parameters);
	}
	
	/**
	 * 
	 */
	public function sendMessageVorlage($parameters)
	{
		return $this->save('system/Message/MessageVorlage', $parameters);
	}
	
	/**
	 * 
	 */
	public function changeMessageStatus($parameters)
	{
		return $this->save('system/Message/ChangeStatus', $parameters);
	}
}