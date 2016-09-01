<?php
/**
 * ./cis/application/models/Oe_model.php
 *
 * @package default
 */


class Oe_model extends MY_Model
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
	public function getOrganisationseinheit($oe_kurzbz) {
		if ($restquery = $this->rest->get('organisation/organisationseinheit/organisationseinheit', array("oe_kurzbz" => $oe_kurzbz))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}
