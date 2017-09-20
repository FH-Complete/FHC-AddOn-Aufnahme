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
	public function getMessagesByPersonId()
	{
		return $this->load('system/Message/MessagesByPersonId', array('person_id' => $this->getPersonId(), 'oe_kurzbz' => $this->config->item('root_oe_stg')));
	}
	
	/**
	 * 
	 */
	public function getSentMessagesByPersonId()
	{
		return $this->load('system/Message/SentMessagesByPerson', array('person_id' => $this->getPersonId(), 'oe_kurzbz' => $this->config->item('root_oe_stg')));
	}
	
	/**
	 * 
	 */
	public function getCountUnreadMessages()
	{
		return $this->loadOne('system/Message/CountUnreadMessages', array('person_id' => $this->getPersonId(), 'oe_kurzbz' => $this->config->item('root_oe_stg')));
	}
	
	/**
	 * 
	 */
	public function getMessageByToken($token)
	{
		$message = $this->loadOne('system/Message/MessagesByToken', array('token' => $token));
		if (hasData($message))
		{
			if ($message->retval->receiver_id == $this->getPersonId())
			{
				return $message;
			}
		}
	}
	
	/**
	 * 
	 */
	public function sendMessage($subject, $body, $oe_kurzbz, $relationMessage_id = null)
	{
		return $this->save(
			'system/Message/Message',
			array(
				'person_id' => $this->getPersonId(),
				'subject' => $subject,
				'body' => $body,
				'oe_kurzbz' => $oe_kurzbz,
				'relationMessage_id' => $relationMessage_id
			)
		);
	}
	
	/**
	 *
	 */
	public function sendMessageVorlage($vorlage_kurzbz, $oe_kurzbz, $data, $sprache, $orgform_kurzbz = null, $sender_id = null, $multiPartMime = true, $receiver_id = null)
	{
		return $this->save(
			'system/Message/MessageVorlage',
			array(
				'vorlage_kurzbz' => $vorlage_kurzbz,
				'oe_kurzbz' => $oe_kurzbz,
				'data' => $data,
				'sprache' => ucfirst($sprache),
				'orgform_kurzbz' => $orgform_kurzbz,
				'relationmessage_id' => $sender_id,
				'multiPartMime' => $multiPartMime,
				'receiver_id' => $receiver_id
			)
		);
	}
	
	/**
	 * 
	 */
	public function changeMessageStatus($message_id, $status)
	{
		return $this->save(
			'system/Message/ChangeStatus',
			array(
				'person_id' => $this->getPersonId(),
				'message_id' => $message_id,
				'status' => $status
			)
		);
	}
}