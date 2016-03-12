<?php
class Person extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('person_model');
        }

        public function index()
        {
                $this->person_model->getPersonen();
				$persondata['person'] = $this->person_model->result->data;
				$data['title'] = 'Personen Archiv';

				$this->load->view('templates/header', $data);
				$this->load->view('person/index', $persondata);
				$this->load->view('templates/footer');
        }

        public function view($id = NULL)
        {
            $this->person_model->getPersonen();
			$persondata['person'] = $this->person_model->result->data[0];
			if (empty($persondata['person']))
		    {
		            show_404();
		    }

		    $data['title'] = $persondata['person']->titelpre;

		    $this->load->view('templates/header', $data);
		    $this->load->view('person/view', $persondata);
		    $this->load->view('templates/footer');
        }
}
