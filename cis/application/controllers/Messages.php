<?php
/**
 * ./cis/application/controllers/Messages.php
 *
 * @package default
 */


class Messages extends MY_Controller {

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('message_model', "MessageModel");
		$this->load->model('prestudent_model', "PrestudentModel");
		$this->load->model('studiengang_model', "StudiengangModel");
		// $this->load->model('oe_model', 'OeModel');
		$this->load->model('person_model', 'PersonModel');
		$this->load->helper("form");
		$this->load->library("form_validation");
		$this->config->load('message');

		$this->_data["sprache"] = $this->get_language();
		$this->_data["view"] = "messages";
	}


	/**
	 *
	 */
	public function index() {
		$this->checkLogin();

		$this->_loadData();

		$this->_loadLanguage($this->_data["sprache"]);

		$this->load->view('messages', $this->_data);
	}


	/**
	 *
	 */
	public function newMessage() {
		$this->checkLogin();

		$this->_loadData();

		$this->_data["view"] = "newMessage";
		$this->load->view('messages', $this->_data);
	}


	/**
	 *
	 * @param unknown $message_id
	 * @param unknown $oe_kurzbz  (optional)
	 */
	public function answerMessage($message_id, $oe_kurzbz=null) {
		$this->checkLogin();

		$this->_loadData();

		foreach ($this->_data["messages"] as $msg) {
			if ($msg->message_id === $message_id) {
				$this->_data["msg"] = $msg;
			}
		}

		$this->_data["oe_kurzbz"] = $oe_kurzbz;
		$this->_data["message_id"] = $message_id;

		$this->_data["view"] = "newMessage";
		$this->load->view('messages', $this->_data);
	}


	public function sendMessage() {
		$this->checkLogin();

		$this->_loadData();

		//form validation rules
		$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
		$this->form_validation->set_rules("msg_subject", "Subject", "required");
		$this->form_validation->set_rules("msg_oe_kurzbz", "Empfänger", "required");
		$this->form_validation->set_rules("msg_body", "Inhalt", "required");

		if ($this->form_validation->run() == FALSE) {
			if (isset($this->input->post()["msg_relationMessage_id"])) {
				$this->_data["oe_kurzbz"] = $this->input->post()["msg_oe_kurzbz"];
				$this->_data["message_id"] = $this->input->post()["msg_relationMessage_id"];
			}
			$this->_data["view"] = "newMessage";
			$this->load->view('messages', $this->_data);
		}
		else {
			$relationMessage_id = null;
			if (isset($this->input->post()["msg_relationMessage_id"]) && ($this->input->post()["msg_relationMessage_id"] !== ""))
			{
				$relationMessage_id = $this->input->post()["msg_relationMessage_id"];
			}

			$this->_sendMessage(
				$this->session->userdata()["person_id"],
				$this->input->post()["msg_subject"],
				$this->input->post()["msg_body"],
				$this->input->post()["msg_oe_kurzbz"],
				$relationMessage_id);

			$this->load->view('messages', $this->_data);
		}
	}


	public function viewMessage($messageId)
	{
		$this->checkLogin();
		$this->_loadData();

		if($this->MessageModel->isResultValid() === true)
		{
			foreach($this->_data["messages"] as $msg)
			{
				if($msg->message_id === $messageId)
				{
					$this->_data["msg"] = $msg;
					$this->_changeMessageStatus($this->session->userdata()["person_id"], $msg, MSG_STATUS_READ);
				}
			}

			$this->_data["view"] = "message";
			$this->load->view('messages', $this->_data);
		}
	}


	public function deleteMessage($messageId)
	{
		$this->checkLogin();
		$this->_loadData();

		if($this->MessageModel->isResultValid() === true)
		{
			foreach($this->_data["messages"] as $msg)
			{
				if($msg->message_id === $messageId)
				{
					$this->_data["msg"] = $msg;
					$this->_changeMessageStatus($this->session->userdata()["person_id"], $msg, MSG_STATUS_DELETED);
				}
			}
		}

		$this->_loadData();
		$this->load->view('messages', $this->_data);
	}
	
	public function changeMessageStatus()
	{
		if((isset($this->input->post()["message_id"])) &&(isset($this->input->post()["status"])))
		{
			$this->_data["messages"] = $this->_getMessages($this->session->userdata("person_id"));
			foreach($this->_data["messages"] as $msg)
			{
				if(($msg->message_id == $this->input->post()["message_id"]))
				{
					$status = $this->input->post()["status"];
					$result = $this->_changeMessageStatus($this->session->userdata("person_id"), $msg, $status);
					if((count($result) == 1) && (isset($result[0]->message_id)))
					{
						$return = new stdClass();
						$return->error = 0;
						echo json_encode($return);
						return;
					}
				}
			}
		}
		else
		{
			//TODO param missing
		}
	}


	private function _loadData()
	{
		//load person data
		$this->_data["person"] = $this->_loadPerson();

		$this->_data["prestudent"] = $this->_loadPrestudent($this->session->userdata()["person_id"]);

		$this->_data["studiengaenge"] = array();
		//TODO optimize
		foreach($this->_data["prestudent"] as $prestudent)
		{
			if($prestudent->studiengang_kz != null)
			{
				$studiengang = $this->_loadStudiengnag($prestudent->studiengang_kz);
				$this->_data["studiengaenge"][$studiengang->oe_kurzbz] = $studiengang->bezeichnung;
			}
		}

		$this->_data["messages"] = $this->_getMessages($this->session->userdata()["person_id"]);
//		var_dump($this->_data["messages"]);
		
//		$this->_data["messages_outbox"] = array();
		$this->_data["messages_outbox"] = $this->_getSentMessages($this->session->userdata()["person_id"]);
//		var_dump($this->_data["messages_outbox"]);
		

	}


	private function _sendMessage($person_id, $subject, $body, $oe_kurzbz, $relationMessage_id = null)
	{
		$message = array(
			"person_id" => $this->session->userdata()["person_id"],
			"receiver_id" => $person_id,
			"subject" => $subject,
			"body" => $body,
			"oe_kurzbz" => $oe_kurzbz
		);

		if(!is_null($relationMessage_id))
		{
			$message["relationmessage_id"] = $relationMessage_id;
		}

		$this->MessageModel->sendMessage($message);
		if($this->MessageModel->isResultValid() === true)
		{
			return $this->MessageModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}


	private function _getMessages($person_id)
	{
		$this->MessageModel->getMessagesByPersonId($person_id);
		if($this->MessageModel->isResultValid() === true)
		{
			//     var_dump($this->MessageModel->result);
			return $this->MessageModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}
	
	private function _getSentMessages($person_id)
	{
		$this->MessageModel->getSentMessagesByPersonId($person_id);
		if($this->MessageModel->isResultValid() === true)
		{
			//     var_dump($this->MessageModel->result);
			return $this->MessageModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}


	private function _loadPrestudent($person_id)
	{
		$this->PrestudentModel->getPrestudent(array("person_id"=>$person_id));
		if($this->PrestudentModel->isResultValid() === true)
		{
			return $this->PrestudentModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->PrestudentModel->getErrorMessage());
		}
	}


	private function _loadStudiengnag($studiengang_kz)
	{
		$this->StudiengangModel->getStudiengang($studiengang_kz);
		if($this->StudiengangModel->isResultValid() === true)
		{
			return $this->StudiengangModel->result->retval[0];
		}
		else
		{
			$this->_setError(true, $this->StudiengangModel->getErrorMessage());
		}
	}


	//    private function _loadOrganisationseinheit($oe_kurzbz)
	//    {
	// $this->OeModel->getOrganisationseinheit($oe_kurzbz);
	// if($this->OeModel->isResultValid() === true)
	// {
	//     return $this->OeModel->result->retval[0];
	// }
	// else
	// {
	//     $this->_setError(true, $this->OeModel->getErrorMessage());
	// }
	//    }

	private function _changeMessageStatus($person_id, $msg, $status)
	{
		$this->MessageModel->changeMessageStatus($person_id, $msg->message_id, $status);
		if($this->MessageModel->isResultValid() === true)
		{
			return $this->MessageModel->result->retval;
		}
		else
		{
			$this->_setError(true, $this->MessageModel->getErrorMessage());
		}
	}


	private function _loadPerson()
	{
		$this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"]));
		if($this->PersonModel->isResultValid() === true)
		{
			if(count($this->PersonModel->result->retval) == 1)
			{
				return $this->PersonModel->result->retval[0];
			}
			else
			{
				return $this->PersonModel->result->retval;
			}
		}
		else
		{
			$this->_setError(true, $this->PersonModel->getErrorMessage());
		}
	}


}
