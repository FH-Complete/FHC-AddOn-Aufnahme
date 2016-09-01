<?php
/**
 * ./cis/application/models/Benutzer_model.php
 *
 * @package default
 */


class Benutzer_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $uid
	 * @return unknown
	 */
	public function getBenutzer($uid) {
		if ($restquery = $this->rest->get('person/benutzer/benutzer', array("uid" => $uid, 'json'))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}
