<?php
/**
 * ./cis/application/models/Kontakt_model.php
 *
 * @package default
 */


class Kontakt_model extends MY_Model
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
	public function saveKontakt($data) {
		if ($restquery = $this->rest->post('person/kontakt/kontakt', $data)) {
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
	public function getKontakt($person_id = NULL) {
		if ($restquery = $this->rest->get('person/kontakt/onlyKontaktByPersonId', array("person_id" => $person_id))) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
