<?php
class Person_model extends CI_Model 
{
	public function __construct()
	{
		$this->load->database();
	}

	public function get_personen($person_id = FALSE)
	{
		    if ($person_id === FALSE)
		    {
		            $query = $this->db->get_where('public.tbl_person', array('vorname' => 'Christian'));
		            return $query->result_object();
		    }

		    $query = $this->db->get_where('public.tbl_person', array('person_id' => $person_id));
		    return $query->row_object();
	}

	public function get_personfromcode($code, $email = null)
	{
		    if (is_null($email))
		    {
		        $query = $this->db->get_where('public.tbl_person', array('zugangscode' => $code));
		        return $query->result_object();
		    }
			else
			{
				$query = $this->db->get_where('public.tbl_person', array('person_id' => $person_id));
		    	return $query->row_object();
			}

	}
}
