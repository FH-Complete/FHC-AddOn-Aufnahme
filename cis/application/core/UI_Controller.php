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
	const NOT_CHECK_LOGIN = false;
	
	private $_data;
	
	/**
	 * 
	 */
	public function __construct($checkLogin = true)
	{
		parent::__construct();
		
		// 
		$this->_data = array();
		
		// Load return message helper
		$this->load->helper('message');
		
		// Loading the 
		$this->load->model('Language_model', 'LanguageModel');
		
		// Loading the 
		$this->load->model('CheckUserAuth_model', 'CheckUserAuthModel');
		
		// 
		if ($checkLogin === true)
		{
			$this->_checkLogin();
		}
	}
	
	/**
	 * 
	 */
	private function _checkLogin()
	{
		if (!$this->CheckUserAuthModel->isLogged()) redirect('/Registration');
	}
	
	/**
	 * 
	 */
	protected function getCurrentLanguage()
	{
		return success($this->LanguageModel->getCurrentLanguage());
	}
	
	/**
	 * 
	 */
	protected function setCurrentLanguage($language)
	{
		$this->LanguageModel->setCurrentLanguage($language);
	}
	
	/**
	 * 
	 */
	protected function setData($name, $response)
	{
		if (hasData($response))
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