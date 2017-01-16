<?php
/**
 * ./cis/application/core/MY_Controller.php
 *
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class UI_Controller extends CI_Controller
{
	private $_data;
	
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$this->_data = array();
		
		// Load return message helper
		$this->load->helper('message');
		
		// Loading the 
		$this->load->model('Language_model', 'LanguageModel');
	}
	
	/**
	 * 
	 */
	protected function getCurrentLanguage()
	{
		return success($this->LanguageModel->getCurrentLanguage($this->input->get('language')));
	}
	
	/**
	 * 
	 */
	protected function setData($name, $response)
	{
		if (isSuccess($response))
		{
			$this->_data[$name] = $response->retval;
		}
		else
		{
			$this->_data[$name] = null;
		}
	}
	
	/**
	 * 
	 */
	protected function setRawData($name, $value)
	{
		$this->_data[$name] = $value;
	}
	
	/**
	 * 
	 */
	protected function getData($name)
	{
		$data = null;
		
		if (isset($this->_data[$name]))
		{
			$data = $this->_data[$name];
		}
		
		return $data;
	}
	
	/**
	 * 
	 */
	protected function getAllData()
	{
		return $this->_data;
	}
	
	/**
	 * 
	 */
	protected function clearData()
	{
		$this->_data = array();
	}
}