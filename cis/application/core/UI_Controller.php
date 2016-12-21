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
	private $data;
	
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 
		$this->data = array();
		
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
	protected function setData($name, $val)
	{
		$this->data[$name] = $val;
	}
	
	/**
	 * 
	 */
	protected function getData($name)
	{
		return $this->data[$name];
	}
	
	/**
	 * 
	 */
	protected function getAllData()
	{
		return $this->data;
	}
	
	/**
	 * 
	 */
	protected function clearData()
	{
		$this->data = array();
	}
}