<?php
class Overview extends MY_Controller {

        public function __construct()
        {
                parent::__construct();
                //$this->load->model('overview_model');
        }

        public function index()
        {
            $this->_data['title'] = 'Overview';
			$this->load->view('overview', $this->_data);
        }

}
