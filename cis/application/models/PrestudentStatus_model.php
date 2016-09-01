<?php
/**
 * ./cis/application/models/PrestudentStatus_model.php
 *
 * @package default
 */


class PrestudentStatus_model extends MY_Model
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
	public function getPrestudentStatus($data) {
		if ($restquery = $this->rest->get('crm/prestudentstatus/prestudentstatus', $data)) {
			$this->result = $restquery;
			return true;
		}

		return false;
	}


	/**
	 *
	 * @param unknown $data
	 * @return unknown
	 */
	public function savePrestudentStatus($data) {
		if ($restquery = $this->rest->post('crm/prestudentstatus/prestudentstatus', $data)) {
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
	public function getLastStatus($data) {
		if ($restquery = $this->rest->get('crm/prestudentstatus/laststatus', $data)) {
			$this->result = $restquery;
			return true;
		}

		return false;
	}


}
