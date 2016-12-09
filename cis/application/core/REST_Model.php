<?php
/**
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

class REST_Model extends CI_Model
{
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
	public function load($resource, $parameters = null, $sessionName = null)
	{
		if ($this->_logged())
		{
			if (isset($sessionName) && $sessionName != '' && isset($this->session->{$sessionName}))
			{
				return $this->session->{$sessionName};
			}
			else
			{
				$response = $this->_checkResponse($this->rest->get($resource, $parameters));
				
				if (isset($sessionName) && $sessionName != '' && isSuccess($response))
				{
					$this->session->set_userdata($sessionName, $response);
				}
				
				return $response;
			}
		}
		else
		{
			return error('Not logged in');
		}
	}
	
	/**
	 * 
	 */
	public function save($resource, $parameters)
	{
		if ($this->_logged())
		{
			if (is_array($parameters) && count($parameters) > 0)
			{
				return $this->_checkResponse($this->rest->post($resource, json_encode($parameters), 'json'));
			}
			else
			{
				return error('Parameters must be a filled array');
			}
		}
		else
		{
			return error('Not logged in');
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
	
	/**
	 *
	 */
	private function _logged()
	{
		if (!isset($this->session->person_id) || (isset($this->session->person_id) && !is_numeric($this->session->person_id)))
		{
			return false;
		}
		
		return true;
	}
}