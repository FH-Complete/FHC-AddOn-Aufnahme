<?php
/**
 * ./cis/application/models/Reihungstest_model.php
 *
 * @package default
 */


class Reihungstest_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $reihungstest_id
	 * @return unknown
	 */
	public function getReihungstest($reihungstest_id) {
		if ($restquery = $this->rest->get('crm/reihungstest/reihungstest', array("reihungstest_id" => $reihungstest_id, 'json'))) {
			$this->result = $restquery;
			return true;
		}
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @param unknown $studiensemester_kurzbz
	 * @return unknown
	 */
	public function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz) {
		if ($restquery = $this->rest->get('crm/reihungstest/ByStudiengangStudiensemester', array("studiengang_kz" => $studiengang_kz, "studiensemester_kurzbz"=> $studiensemester_kurzbz, 'json'))) {
			$this->result = $restquery;
			return true;
		}
	}


	/**
	 *
	 * @param unknown $person_id
	 * @return unknown
	 */
	public function getReihungstestByPersonID($person_id) {
		if ($restquery = $this->rest->get('crm/reihungstest/reihungstestByPersonId', array("person_id" => $person_id, 'json'))) {
			$this->result = $restquery;
			return true;
		}
	}


}
