<?php
/**
 * ./cis/application/models/DokumentStudiengang_model.php
 *
 * @package default
 */


class DokumentStudiengang_model extends MY_Model
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
	 * @param unknown $studiengang_kz
	 * @param unknown $onlinebewerbung
	 * @param unknown $pflicht
	 * @return unknown
	 */
	public function getDokumentstudiengangByStudiengang_kz($studiengang_kz, $onlinebewerbung, $pflicht) {
		if ($restquery = $this->rest->get('crm/dokumentstudiengang/DokumentstudiengangByStudiengang_kz', array("studiengang_kz"=>$studiengang_kz, "onlinebewerbung"=>$onlinebewerbung, "pflicht"=>$pflicht))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


}
