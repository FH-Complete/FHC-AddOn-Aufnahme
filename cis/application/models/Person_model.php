<?php
/**
 * ./cis/application/models/Person_model.php
 *
 * @package default
 */


class Person_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $person_id (optional)
	 * @return unknown
	 */
	public function getPersonen($person_id = NULL) {
		if (is_array($person_id))
			$persondata = $person_id;
		else
			$persondata = array("person_id" => $person_id);

		if ($restquery = $this->rest->get('person/person/person', $persondata)) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $code
	 * @param unknown $email (optional)
	 * @return unknown
	 */
	public function getPersonFromCode($code, $email = null) {
		$data = array(
			'code' => $code,
			'email' => $email
		);

		if ($restquery = $this->rest->get('person/person/person', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function savePerson($data) {
		if ($restquery = $this->rest->postJson('person/person/person', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	public function updatePerson($data) {
		if ($restquery = $this->rest->postJson('person/person/person', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	public function checkBewerbung($data) {
		if ($restquery = $this->rest->get('person/person/CheckBewerbung', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	public function checkZugangscodePerson($code) {
		if ($restquery = $this->rest->get('person/person/person', $code)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
