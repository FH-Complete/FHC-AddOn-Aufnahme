<?php
/**
 * ./cis/application/models/Dokument_model.php
 *
 * @package default
 */


class Dokument_model extends MY_Model
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
	 * @param unknown $dokument_kurzbz
	 * @return unknown
	 */
	public function getDokument($dokument_kurzbz) {
		if ($restquery = $this->rest->get('crm/dokument/Dokument', array("dokument_kurzbz"=>$dokument_kurzbz))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}
