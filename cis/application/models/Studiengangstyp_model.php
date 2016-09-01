<?php
/**
 * ./cis/application/models/Studiengangstyp_model.php
 *
 * @package default
 */


if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Studiengangstyp_model extends MY_Model
{


	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $typ
	 * @return unknown
	 */
	function get($typ) {
		if ($restquery = $this->rest->get('organisation/studiengangstyp/studiengangstyp', array("typ" => $typ))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}


/* End of file Studiengang_model.php */
/* Location: ./application/models/Studiengang_model.php */
