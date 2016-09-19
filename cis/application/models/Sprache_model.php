<?php
/**
 * ./cis/application/models/Oe_model.php
 *
 * @package default
 */


class Sprache_model extends MY_Model
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
	 * @param unknown $oe_kurzbz
	 * @return unknown
	 */
	public function getSprache($sprache) {
		if ($restquery = $this->rest->get('system/sprache/sprache', array("sprache" => $sprache))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}
