<?php
class Person extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('person_model');
        }

        public function index()
        {
            $data['sprache'] = $this->get_language();
			$data['title'] = 'Personendate';
			$this->load->view('person', $data);
        }

}
