<?php

/**
 * 
 */
class Language_model extends CI_Model
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load session library
		$this->load->library('session');
		
		// Loading the addon configuration
		$this->load->config('aufnahme');
	}

	/**
	 * 
	 */
	public function getCurrentLanguage()
	{
		if (!isset($this->session->language))
		{
			$this->session->language = $this->config->item('default_language');
		}
		
		return $this->session->language;
	}
	
	/**
	 * 
	 */
	public function setCurrentLanguage($language)
	{
		if (isset($language) && $language != '')
		{
			$this->session->language = strtolower($language);
		}
	}
}