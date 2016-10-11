<?php
/**
 * ./cis/application/models/Akte_model.php
 *
 * @package default
 */


class Akte_model extends MY_Model
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
	public function saveAkte($data) {
		if ($restquery = $this->rest->post('crm/akte/akte', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @param unknown $person_id          (optional)
	 * @param unknown $dokumenttyp_kurzbz (optional)
	 * @return unknown
	 */
	public function getAkten($person_id = NULL, $dokumenttyp_kurzbz = null) {
		if ($restquery = $this->rest->get('crm/akte/akten', array("person_id" => $person_id, "dokumenttyp_kurzbz"=> $dokumenttyp_kurzbz))) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 *
	 * @param unknown $person_id          (optional)
	 * @param unknown $dokumenttyp_kurzbz (optional)
	 * @return unknown
	 */
	public function getAktenAccepted($person_id = NULL, $dokumenttyp_kurzbz = null) {
		if ($restquery = $this->rest->get('crm/akte/aktenaccepted', array("person_id" => $person_id, "dokumenttyp_kurzbz"=> $dokumenttyp_kurzbz))) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}

}
