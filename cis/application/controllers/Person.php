<?php
class Person extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('person_model');
        }

        public function index()
        {
            $this->_data['title'] = 'Personendaten';
			$this->load->view('person', $this->_data);
        }

}
