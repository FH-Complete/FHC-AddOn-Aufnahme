<?php
class Contact extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                //$this->load->model('contact_model');
        }

        public function index()
        {
            //$data['sprache'] = $this->get_language();
			$this->_data['title'] = 'Kontaktdaten';
			$this->load->view('contact', $this->_data);
        }

}
