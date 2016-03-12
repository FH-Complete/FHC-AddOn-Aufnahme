<?php
class Person_model extends MY_Model 
{
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
	}

	public function getPersonen($person_id = FALSE)
	{
		    if ( $restquery = $this->rest->get('Person/person') )
			{
				$this->result=$restquery;
				return true;
			}
			else
				return false;
	}

	public function getPersonFromCode($code, $email = null)
	{
		$data = array('code' =>'1g423a45s67g89');
		   if ( $restquery = $this->rest->get('Person/person',$data) )
			{
				$this->result=$restquery;
				return true;
			}
			else
				return false;
	}
}
