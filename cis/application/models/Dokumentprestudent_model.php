<?php
/**
 * ./cis/application/models/DokumentStudiengang_model.php
 *
 * @package default
 */


class Dokumentprestudent_model extends MY_Model
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
	 * @param type $prestudent_id
	 * @param type $studiengang_kz
	 * @return boolean
	 */
	public function setAccepted($prestudent_id, $studiengang_kz) {
		if ($restquery = $this->rest->postJson('crm/Dokumentprestudent/SetAccepted', array("prestudent_id"=>$prestudent_id, "studiengang_kz"=>$studiengang_kz))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}

	/**
	 * 
	 * @param type $prestudent_id
	 * @param type $studiengang_kz
	 * @param array $dokument_kurzbz_array
	 * @return boolean
	 */
	public function setAcceptedDocuments($prestudent_id, $studiengang_kz, $dokument_kurzbz_array) {
		if ($restquery = $this->rest->postJson('crm/Dokumentprestudent/SetAcceptedDocuments', array("prestudent_id"=>$prestudent_id, "studiengang_kz"=>$studiengang_kz, "dokument_kurzbz"=>$dokument_kurzbz_array))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}

}
