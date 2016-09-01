<?php
/**
 * ./cis/application/core/MY_Model.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $result;


	/**
	 *
	 */
	function __construct() {
		parent::__construct();
		//$this->load->library('curl');
		//$this->load->spark('restclient/2.1.0');
		//$this->load->spark('codeigniter-restclient');
		$this->load->library('rest');
		$config = $this->config->item('fhc_api');
		$this->rest->initialize($config);
		$this->rest->api_key($config['api_key'], $config['api_name']);
	}


	/**
	 *
	 */
	function coreapi_login() {
		$this->load
		->add_package_path(APPPATH . 'third_party/restclient')
		->library('restclient')
		->remove_package_path(APPPATH . 'third_party/restclient');

		$json = $this->restclient->post(site_url('server'), [
				'lastname' => 'test'
			]);

		$this->restclient->debug();
	}



	/**
	 * returns true if the result property is an object and
	 * the error property is equal to zero, which means that the rest
	 * query was successful
	 *
	 * @return boolean
	 */
	public function isResultValid() {
		if (is_object($this->result)) {
			if (isset($this->result->error)) {
				if ($this->result->error === 0) {
					return true;
				}
				else {
					return $this->result->error;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}


	/**
	 *
	 * @return unknown
	 */
	public function getErrorMessage() {
		if (is_object($this->result))
		{
			if(isset($this->result->error))
			{
				$msg = "";
				if(isset($this->result->retval))
				{
					if(is_string($this->result->retval))
					{
						$msg = $this->result->retval;
						return "Error Code: ".$this->result->error."; ".$msg."; ". (isset($this->result->msg) ? $this->result->msg : "");
					}
				}
				else
				{
					$msg = $this->result->error;
					return "Error: ".$msg;
				}

			}
		}
		else
		{
			return $this->result;
		}
	}


}
