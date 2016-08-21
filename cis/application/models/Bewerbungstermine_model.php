<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bewerbungstermine_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function getByStudiengangStudiensemester($studiengang_kz, $studiensemester_kurzbz)
    {
        if ($restquery = $this->rest->get('crm/bewerbungstermine/ByStudiengangStudiensemester', array("studiengang_kz"=>$studiengang_kz, "studiensemester_kurzbz"=>$studiensemester_kurzbz)))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
}

/* End of file Studiengang_model.php */
/* Location: ./application/models/Studiengang_model.php */

