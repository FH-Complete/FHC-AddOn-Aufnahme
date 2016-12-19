<?php

/**
 * 
 */
class Message extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model MessageModel
		$this->load->model('system/Message_model', 'MessageModel');
	}
	
	/**
	 * 
	 */
	public function getMessagesByPersonId($person_id)
	{
		$this->response($this->MessageModel->getMessagesByPersonId($person_id));
	}
	
	/**
	 * 
	 */
	public function getSentMessagesByPersonId($person_id)
	{
		$this->response($this->MessageModel->getSentMessagesByPersonId($person_id));
	}
}