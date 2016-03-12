<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader 
{
	function __construct()  
	{
        parent::__construct();
    }

	function load_views($view)
	{
		if (!is_null($this->config->item($view)))
		{
			foreach($this->config->item($view) as $v)
				$this->load->view($v);
		}
	}
}
