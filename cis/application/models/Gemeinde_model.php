<?php
/**
 * ./cis/application/models/Gemeinde_model.php
 *
 * @package default
 */


class Gemeinde_model extends MY_Model
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
	 * @return unknown
	 */
	public function getGemeinde() {
		if (isset($this->session->userdata()["gemeinde"])) {
			$this->result= $this->session->userdata()["gemeinde"];
			return true;
		}
		else {
			if ($restquery = $this->rest->get('codex/gemeinde/gemeinde')) {
				$this->result = $restquery;
				$this->session->set_userdata("gemeinde", $this->result);
				return true;
			}
			else
				return false;
		}
	}


}
