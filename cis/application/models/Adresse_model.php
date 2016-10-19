<?php
/**
 * ./cis/application/models/Adresse_model.php
 *
 * @package default
 */


class Adresse_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		//$this->load->database();
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function saveAdresse($data) {
		if ($restquery = $this->rest->postJson('person/adresse/adresse', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @param unknown $person_id (optional)
	 * @return unknown
	 */
	public function getAdresse($person_id = NULL) {
		if ($restquery = $this->rest->get('person/adresse/adresse', array("person_id" => $person_id))) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
