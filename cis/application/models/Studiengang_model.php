<?php
/**
 * ./cis/application/models/Studiengang_model.php
 *
 * @package default
 */


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Studiengang_model extends MY_Model
{


	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @return unknown
	 */
	function getAll() {
		if ($restquery = $this->rest->get('organisation/studiengang/allForBewerbung')) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $stgkz
	 * @return unknown
	 */
	function getStudiengang($stgkz) {
		if ($restquery = $this->rest->get('organisation/studiengang/studiengang', array("studiengang_kz"=>$stgkz))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $studiensemester_kurzbz
	 * @param unknown $ausbildungssemester
	 * @return unknown
	 */
	function getStudiengangStudienplan($studiensemester_kurzbz, $ausbildungssemester) {
		if ($restquery = $this->rest->get('organisation/studiengang/studiengangStudienplan', array("studiensemester_kurzbz"=>$studiensemester_kurzbz, "ausbildungssemester"=>$ausbildungssemester))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}

	/**
	 *
	 * @param unknown $studiensemester_kurzbz
	 * @param unknown $ausbildungssemester
	 * @return unknown
	 */
	function getStudiengangBewerbung()
	{
		if ($restquery = $this->rest->get('organisation/studiengang/studiengangBewerbung'))
		{
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}

	function getCompleteStudiengang($studiensemester_kurzbz, $ausbildungssemester) {
		if ($restquery = $this->rest->get('organisation/studiengang/completeStudiengang', array("studiensemester_kurzbz"=>$studiensemester_kurzbz, "ausbildungssemester"=>$ausbildungssemester))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}


/* End of file Studiengang_model.php */
/* Location: ./application/models/Studiengang_model.php */
