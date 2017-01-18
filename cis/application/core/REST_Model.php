<?php
/**
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

class REST_Model extends CI_Model
{
	const AUTH_NOT_REQUIRED = true;
	
	private $_personSessionName = 'Person.getPerson';
	
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
	public function load($resource, $parameters = null, $sessionName = null, $authNotRequired = false)
	{
		if ($authNotRequired || $this->_logged())
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
					$this->session->{$sessionName} = $response;
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
	public function loadOne($resource, $parameters = null, $sessionName = null, $authNotRequired = false)
	{
		$result = $this->load($resource, $parameters, $sessionName, $authNotRequired);
		
		if (hasData($result))
		{
			if (is_array($result->retval))
			{
				$result = success($result->retval[0], $result->fhcCode);
			}
			else
			{	
				$result = success($result->retval, $result->fhcCode);
			}
			
			$this->session->{$sessionName} = $result;
		}
		
		return $result;
	}
	
	/**
	 * 
	 */
	public function save($resource, $parameters, $sessionName = null, $authNotRequired = false)
	{
		if ($authNotRequired || $this->_logged())
		{
			if (is_array($parameters) && count($parameters) > 0)
			{
				$result = $this->_checkResponse($this->rest->post($resource, json_encode($parameters), 'json'));
				
				if (isSuccess($result) && isset($sessionName) && isset($this->session->{$sessionName}))
				{
					unset($this->session->{$sessionName});
				}
				
				return $result;
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
	public function delete($resource, $parameters, $sessionName = null)
	{
		if ($this->_logged())
		{
			if (is_array($parameters) && count($parameters) > 0)
			{
				$result = $this->_checkResponse($this->rest->delete($resource, $parameters));
				
				if (isSuccess($result) && isset($sessionName) && isset($this->session->{$sessionName}))
				{
					unset($this->session->{$sessionName});
				}
				
				return $result;
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
	protected function getPersonId()
	{
		$person_id = null;
		
		if (isset($this->session->{$this->_personSessionName}))
		{
			$person = $this->session->{$this->_personSessionName};
			if (hasData($person))
			{
				$person_id = $person->retval->person_id;
			}
		}
		
		return $person_id;
	}
	
	/**
	 * 
	 */
	protected function storeSession($name, $value)
	{
		$this->session->{$name} = $value;
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
	protected function _logged()
	{
		if (isset($this->session->{$this->_personSessionName}))
		{
			$person = $this->session->{$this->_personSessionName};
			if (hasData($person))
			{
				if (isset($person->retval->person_id) && is_numeric($person->retval->person_id))
				{
					return true;
				}
			}
		}
		
		return false;
	}
}