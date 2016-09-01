<?php
/**
 * ./cis/application/models/Bundesland_model.php
 *
 * @package default
 */


class Bundesland_model extends MY_Model
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
	public function getBundeslaender() {
		if (isset($this->session->userdata()["bundelaender"])) {
			$this->result= $this->session->userdata()["bundelaender"];
			return true;
		}
		else {
			if ($restquery = $this->rest->get('codex/bundesland/all')) {
				$this->result = $restquery;
				$this->session->set_userdata("bundelaender", $this->result);
				return true;
			}
			else
				return false;
		}
	}


}
