<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{
	function __construct()  
	{
        parent::__construct();
		$this->load->config('aufnahme.dist');
		$this->load->config('aufnahme', FALSE, TRUE);
		$this->load->library('session');
    }

	function get_language()
	{
		if (is_null($this->input->get('sprache')))
		{
			if (is_null($this->session->sprache))
			{
				return $this->config->item('default_language');
			}
			else
				return  $this->session->sprache;
		}
		else
		{
			$this->session->sprache=$this->input->get('sprache');
			return  $this->input->get('sprache');
		}
	}
  
}
