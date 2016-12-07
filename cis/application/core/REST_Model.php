<?php
/**
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

class REST_Model extends CI_Model
{
	protected $sessionName;
	
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
		
		// Load return message helper
		$this->load->helper('message');
		
		// Load session library
		$this->load->library('session');
		
		// Load rest library
		$this->load->library('rest');
		
		// Initialize rest library
		$this->rest->initialize($this->config->item('fhc_api'));
	}
	
	/**
	 * 
	 */
	public function load($resource, $parameters = null, $session = false)
	{
		if ($session === true && isset($this->session->{$this->sessionName}))
		{
			return $this->session->{$this->sessionName};
		}
		else
		{
			$response = $this->_checkResponse($this->rest->get($resource, $parameters));
			
			if ($session === true && isSuccess($response))
			{
				$this->session->set_userdata($this->sessionName, $response);
			}
			
			return $response;
		}
	}
	
	/**
	 * 
	 */
	private function _checkResponse($response)
	{
		if (isError($response))
		{
			return error('Generic error');
		}
		
		return $response;
	}
}