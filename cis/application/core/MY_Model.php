<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model 
{
	public $result;
	
	function __construct()  
	{
        parent::__construct();
		//$this->load->library('curl');
		//$this->load->spark('restclient/2.1.0');
		//$this->load->spark('codeigniter-restclient');
		$this->load->library('rest');
		$config=$this->config->item('fhc_api');
		$this->rest->initialize($config);
		$this->rest->api_key($config['api_key'], $config['api_name']);
		
    }


	function coreapi_login()
	{
		$this->load
            ->add_package_path(APPPATH.'third_party/restclient')
            ->library('restclient')
            ->remove_package_path(APPPATH.'third_party/restclient');

        $json = $this->restclient->post(site_url('server'), [
            'lastname' => 'test'
        ]);

        $this->restclient->debug();
	}
  
}
