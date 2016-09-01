<?php
/**
 * ./cis/application/controllers/Contact.php
 *
 * @package default
 */


class Contact extends MY_Controller {

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		//$this->load->model('contact_model');
	}


	/**
	 *
	 */
	public function index() {
		$this->_data['title'] = 'Kontaktdaten';
		$this->_data['sprache'] = $this->get_language();
		$this->load->view('contact', $this->_data);
	}


}
