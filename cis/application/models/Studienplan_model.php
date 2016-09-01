<?php
/**
 * ./cis/application/models/Studienplan_model.php
 *
 * @package default
 */


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Studienplan_model extends MY_Model
{


	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $studienplan_id
	 * @return unknown
	 */
	function getStudienplan($studienplan_id) {
		if ($restquery = $this->rest->get('organisation/studienplan/studienplan', array("studienplan_id"=>$studienplan_id))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $studiengang_kz
	 * @return unknown
	 */
	function getStudienplaene($studiengang_kz) {
		if ($restquery = $this->rest->get('organisation/studienplan/studienplaene', array("studiengang_kz"=>$studiengang_kz))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	function getStudienplaeneFromSem($data) {
		if ($restquery = $this->rest->get('organisation/studienplan/StudienplaeneFromSem', $data)) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}


/* End of file Studiengang_model.php */
/* Location: ./application/models/Studiengang_model.php */
