<?php
/**
 * ./cis/application/models/Message_model.php
 *
 * @package default
 */


class Message_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $person_id
	 * @return unknown
	 */
	public function getMessagesByPersonId($person_id) {
		if ($restquery = $this->rest->get('system/message/MessagesByPersonId', array("person_id"=>$person_id))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}
	
	/**
	 *
	 * @param unknown $person_id
	 * @return unknown
	 */
	public function getSentMessagesByPersonId($person_id) {
		if ($restquery = $this->rest->get('system/message/SentMessagesByPerson', array("person_id"=>$person_id))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function sendMessage($data) {
		if ($restquery = $this->rest->postJson('system/message/message', $data)) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


    /**
     *
     * @param unknown $vorlage_kurzbz
     * @param unknown $oe_kurzbz
     * @param unknown $data
     * @param unknown $sprache
     * @param unknown $orgform_kurzbz (optional)
     * @param unknown $sender_id
     * @param unknown $receiver_id
     * @param bool $multiPartMime
     * @return unknown
     */
	public function sendMessageVorlage($vorlage_kurzbz, $oe_kurzbz, $data, $sprache, $orgform_kurzbz = null, $sender_id = null, $receiver_id = null, $multiPartMime=true) {
		$message = array(
			"vorlage_kurzbz" => $vorlage_kurzbz,
			"oe_kurzbz" => $oe_kurzbz,
			"data" => $data,
			"sprache" => ucfirst($sprache),
			"orgform_kurzbz" => $orgform_kurzbz,
			"relationmessage_id" => null,
            "multiPartMime" => $multiPartMime
		);
		
		if($receiver_id !== null)
		{
			$message["receiver_id"] = $receiver_id;
		}
		
		if($sender_id !== null)
		{
			$message["sender_id"] = $sender_id;
		}

		if ($restquery = $this->rest->postJson('system/message/messageVorlage', $message)) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	public function changeMessageStatus($person_id, $message_id, $status) {
		if ($restquery = $this->rest->postJson('system/message/changeStatus', array("person_id" => $person_id, "message_id"=>$message_id, "status"=>$status))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}

    /**
     *
     */
    public function getMessagesByToken($token)
    {
        if ($restquery = $this->rest->get('system/Message/MessagesByToken', array('token' => $token))) {
            $this->result = $restquery;
            return true;
        }
        else
            return false;
    }


}
