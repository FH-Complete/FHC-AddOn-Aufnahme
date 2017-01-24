<?php
/**
 * ./cis/application/controllers/Messages.php
 *
 * @package default
 */

class Messages extends UI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->config->load('message');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->setData('sprache', $this->getCurrentLanguage());
		
		$this->load->model('crm/Prestudent_model', 'PrestudentModel');
		$this->load->model('organisation/Studiengang_model', 'StudiengangModel');
		$this->load->model('organisation/Studiensemester_model', 'StudiensemesterModel');
		$this->load->model('person/Person_model', 'PersonModel');
		$this->load->model('system/Message_model', 'MessageModel');
		
		$this->setRawData('view', 'messages');
	}

	/**
	 *
	 */
	public function index()
    {
		$this->_loadData();
		
		$this->load->view('messages', $this->getAllData());
	}

	/**
	 *
	 */
	public function newMessage()
    {
		$this->_loadData();
		
		$this->setRawData('view', 'newMessage');
		
		$this->load->view('messages', $this->getAllData());
	}

	/**
	 *
	 * @param unknown $message_id
	 * @param unknown $oe_kurzbz  (optional)
	 */
	public function answerMessage($message_id, $oe_kurzbz = null)
    {
		if (isset($this->session->userdata()['token']))
        {
            $this->session->unset_userdata('token');
        }
		
		$this->_loadData();
		
		$messages = $this->MessageModel->getMessagesByPersonId();
		if (hasData($messages))
		{
			foreach ($messages->retval as $message)
			{
				if ($message->message_id === $message_id)
				{
					$this->setRawData('msg', $message);
				}
			}
		}
		
		$this->setRawData('oe_kurzbz', $oe_kurzbz);
		$this->setRawData('message_id', $message_id);
		
		$this->setRawData('view', 'newMessage');
		
		$this->load->view('messages', $this->getAllData());
	}


	public function sendMessage()
    {
		$this->_loadData();
		
		//form validation rules
		$this->form_validation->set_error_delimiters('<span class=\'help-block\'>', '</span>');
		$this->form_validation->set_rules('msg_subject', 'Subject', 'required');
		$this->form_validation->set_rules('msg_oe_kurzbz', 'Empfänger', 'required');
		$this->form_validation->set_rules('msg_body', 'Inhalt', 'required');

		if ($this->form_validation->run() == false)
		{
			if (isset($this->input->post()['msg_relationMessage_id']))
			{
				$this->setRawData('oe_kurzbz', $this->input->post()['msg_oe_kurzbz']);
				$this->setRawData('message_id', $this->input->post()['msg_relationMessage_id']);
			}
			
			$this->setRawData('view', 'newMessage');
			
			$this->load->view('messages', $this->getAllData());
		}
		else
		{
			$relationMessage_id = null;
			if (isset($this->input->post()['msg_relationMessage_id']) && $this->input->post()['msg_relationMessage_id'] !== '')
			{
				$relationMessage_id = $this->input->post()['msg_relationMessage_id'];
			}
			
			$this->_sendMessage(
				$this->input->post()['msg_subject'],
				$this->input->post()['msg_body'],
				$this->input->post()['msg_oe_kurzbz'],
				$relationMessage_id
			);
			
			if ($this->getData('error') !== null)
			{
				redirect('/Messages');
			}
			else
			{
				$this->load->view('messages', $this->getAllData());
			}
		}
	}
	
	public function viewMessage($message_id)
	{
		$this->_loadData();
		
		$messages = $this->MessageModel->getMessagesByPersonId();
		
		if (hasData($messages))
		{
			foreach ($messages->retval as $message)
			{
				if ($message->message_id === $message_id)
				{
					$this->setRawData('msg', $message);
					$this->_changeMessageStatus($message, MSG_STATUS_READ);
				}
			}
			
			$this->setRawData('view', 'message');
			
			$this->load->view('messages', $this->getAllData());
		}
	}
	
	public function deleteSentMessage($message_id)
    {
        $this->_loadData();

        $messages = $this->MessageModel->getMessagesByPersonId();
		
		if (hasData($messages))
		{
            foreach ($messages->retval as $message)
            {
                if ($message->message_id === $message_id)
                {
                    $this->setRawData('msg', $message);
                    $this->_changeMessageStatus($message, MSG_STATUS_DELETED);
                }
            }
        }
		
        $this->_loadData();
        
        $this->load->view('messages', $this->getAllData());
    }
    
	public function deleteMessage($message_id)
	{
		$this->_loadData();
		
		$messages = $this->MessageModel->getMessagesByPersonId();
		
		if (hasData($messages))
		{
			foreach ($messages->retval as $message)
			{
				if ($message->message_id === $message_id)
				{
					$this->setRawData('msg', $message);
					$this->_changeMessageStatus($message, MSG_STATUS_DELETED);
				}
			}
		}
		
		$this->_loadData();
		
		$this->load->view('messages', $this->getAllData());
	}
	
	public function changeMessageStatus()
	{
		if (isset($this->input->post()['message_id']) && isset($this->input->post()['status']))
		{
			$messages = $this->MessageModel->getMessagesByPersonId();
			
			if (hasData($messages))
			{
				$this->setData('messages', $messages);
				foreach ($messages-retval as $message)
				{
					if (($messages->message_id == $this->input->post()['message_id']))
					{
						$status = $this->input->post()['status'];
						$result = $this->_changeMessageStatus($messages, $status);
						
						if (hasData($message))
						{
							return success($message);
						}
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
		$this->setData('person', $this->PersonModel->getPerson());
		
		$studiengaengeArray = array();
		
		$studiensemester = $this->StudiensemesterModel->getNextStudiensemester('WS');
		if (hasData($studiensemester))
		{
			$this->setData('studiensemester', $studiensemester);
			$studiengaenge = $this->StudiengangModel->getAppliedStudiengang(
				$this->getData('studiensemester')->studiensemester_kurzbz,
				'',
				'Interessent',
                true
			);
			
			if (hasData($studiengaenge))
			{
				foreach ($studiengaenge->retval as $studiengang)
				{
					$studiengaengeArray[$studiengang->oe_kurzbz] = $studiengang->bezeichnung;
				}
			}
		}
		
		$this->setRawData('studiengaenge', $studiengaengeArray);
		
		$this->setData(
		    'prestudent',
            $this->PrestudentModel->getLastStatuses(
                $this->getData('person')->person_id,
                $this->getData('studiensemester')->studiensemester_kurzbz,
                null,
                null,
                true
            )
		);
		
		$this->setData('numberOfUnreadMessages', $this->MessageModel->getCountUnreadMessages());
		
		$this->setData('messages', $this->MessageModel->getMessagesByPersonId());
		
		$this->setData('messages_outbox', $this->MessageModel->getSentMessagesByPersonId());
	}
	
	private function _sendMessage($subject, $body, $oe_kurzbz, $relationMessage_id = null)
	{
		$result = $this->MessageModel->sendMessage($subject, $body, $oe_kurzbz, $relationMessage_id);
		if (isSuccess($result))
		{
			return $result->retval;
		}
		else
		{
			$this->_setError(true, $result->fhcCode . ' ' . $result->retval);
		}
	}
	
	private function _changeMessageStatus($msg, $status)
	{
		$result = $this->MessageModel->changeMessageStatus($msg->message_id, $status);
		if (isSuccess($result))
		{
			return $result->retval;
		}
		else
		{
			$this->_setError(true, $result->fhcCode . ' ' . $result->retval);
		}
	}
}