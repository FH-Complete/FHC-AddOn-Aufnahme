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
		return $this->LanguageModel->getCurrentLanguage($this->input->get('language'));
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
	protected function getData($name)
	{
		return $this->_data[$name];
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