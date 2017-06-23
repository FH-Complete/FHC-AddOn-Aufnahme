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

        // Loading the
        $this->load->model('system/Sprache_model', 'SpracheModel');

        // Loading the
        $this->load->model('system/UDF_model', 'UdfModel');
		
		// 
		if ($checkLogin === true)
		{
			$this->_checkLogin();
		}

        $this->setRawData("udfs", $this->getUDFs());
	}
	
	/**
	 * 
	 */
	private function _checkLogin()
	{
		if (!$this->CheckUserAuthModel->isLogged()) redirect('/Registration');
	}

	private function _loadUDFs()
    {
        return $this->UdfModel->getUdf();
    }

    protected function _getSprache($sprache)
    {
        $this->SpracheModel->getSprache(ucfirst($sprache));
    }
	
	/**
	 * 
	 */
	protected function getCurrentLanguage()
	{
	    $language = $this->LanguageModel->getCurrentLanguage();
	    $this->_getSprache($language);
		return success($language);
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

	/**
     *
     */
	protected function getUDFs()
    {
        $udfs = $this->_loadUDFs();
        if(isset($udfs->retval[0]) && is_object($udfs->retval[0]))
        {
            $data = json_decode($udfs->retval[0]->jsons);

            if(is_array($data))
            {
                usort($data,"sort_udfs");
            }

            return $data;
        }
        else
        {
            return "";
        }
    }
}

function sort_udfs($a,$b)
{
    if ($a->sort==$b->sort)
    {
        return 0;
    }
    return ($a->sort < $b->sort)?-1:1;
}