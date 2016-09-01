<?php
/**
 * ./cis/application/models/Prestudent_model.php
 *
 * @package default
 */


class Prestudent_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function getPrestudent($data) {
		if (isset($data["prestudent_id"])) {
			if ($restquery = $this->rest->get('crm/prestudent/prestudent', array("prestudent_id" => $data["prestudent_id"], 'json'))) {
				$this->result = $restquery;
				return true;
			}
		}
		elseif (isset($data["person_id"])) {
			if ($restquery = $this->rest->get('crm/prestudent/prestudentByPersonId', array("person_id" => $data["person_id"], 'json'))) {
				$this->result = $restquery;
				return true;
			}
		}

		return false;
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function savePrestudent($data) {

		if ($restquery = $this->rest->post('crm/prestudent/prestudent', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @param unknown $person_id
	 * @param unknown $rt_id
	 * @param unknown $studienplan_id
	 * @return unknown
	 */
	public function registerToReihungstest($person_id, $rt_id, $studienplan_id) {
		$data = new stdClass();
		$data->new = true;
		$data->person_id = $person_id;
		$data->rt_id = $rt_id;
		$data->anmeldedatum = date('Y-m-d');
		$data->studienplan_id = $studienplan_id;

		if ($restquery = $this->rest->post('crm/prestudent/addReihungstest', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	public function deleteRegistrationToReihungstest($reihungstest) {
		if ($restquery = $this->rest->post('crm/prestudent/delReihungstest', $reihungstest)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
