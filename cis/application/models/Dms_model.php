<?php
/**
 * ./cis/application/models/Dms_model.php
 *
 * @package default
 */


class Dms_model extends MY_Model
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
	public function saveDms($data) {
		if ($restquery = $this->rest->post('content/dms/dms', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @param unknown $dms_id
	 * @return unknown
	 */
	public function loadDms($dms_id) {
		if ($restquery = $this->rest->get('content/dms/dms', array("dms_id"=>$dms_id))) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
