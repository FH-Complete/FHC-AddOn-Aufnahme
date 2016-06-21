<?php
class Messages extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
        }

        public function index()
        {
	    $this->_data['title'] = 'Nachrichten';
	    $this->_data["sprache"] = $this->get_language();
	    $this->load->view('messages', $this->_data);
        }
	
	
	/**
	 * 
	 * @param type $person_id sender
	 * @param type $subject betreff
	 * @param type $body inhalt
	 * @param type $oe_kurzbz oe_kurzbz des Empfaengers (Studiengang)
	 */
	private function _sendMessage($person_id, $subject, $body, $oe_kurzbz)
	{
	    
	}
	
	private function _getMessages($person_id)
	{
	    
	}

}
