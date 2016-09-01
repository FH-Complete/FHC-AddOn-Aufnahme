<?php
/**
 * ./cis/application/models/UserAuth_model.php
 *
 * @package default
 */


class UserAuth_model extends MY_Model
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
	public function checkByUsernamePassword($data) {
		if ($restquery = $this->rest->get('checkUserAuth/CheckByUsernamePassword', $data)) {
			$this->result = $restquery;
			return true;
		}
		else {
			return false;
		}
	}


}
