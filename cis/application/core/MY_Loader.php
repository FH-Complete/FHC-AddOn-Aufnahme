<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader 
{
    function __construct()  
    {
	parent::__construct();
    }

    function load_views($view, $data=array())
    {
	if (! is_null($this->config->item($view)))
	{
	    foreach($this->config->item($view) as $v)
		$this->load->view($v, $data);
	}
    }
    
    function getPhrase($phrase, $sprache, $oe_kurzbz = '', $orgform_kurzbz = '')
    {
		if(isset($this->session->userdata()["phrasen"]))
		{
			$phrasen = $this->session->userdata()["phrasen"];
			foreach($phrasen as $p)
			{
				if(($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz))
				{
					if ($this->config->item('display_phrase_name'))
						return $p->phrase;
					else
						return $p->text;
				}
			}
			
			foreach($phrasen as $p)
			{
			if(($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz))
			{
				if ($this->config->item('display_phrase_name'))
				{
				return $p->phrase;
				}
				else
				{
				return $p->text;
				}
			}
	    }
	    
	    foreach($phrasen as $p)
	    {
		if(($p->phrase == $phrase))
		{
		    if ($this->config->item('display_phrase_name'))
		    {
			return $p->phrase;
		    }
		    else
		    {
			return $p->text;
		    }
		}
	    }
	}
	else
	{
	    return "please load phrases first";
	}
    }
}
