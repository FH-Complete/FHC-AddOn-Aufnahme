<?php

class PrestudentStatus_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getPrestudentStatus($data)
    {
        if ($restquery = $this->rest->get('crm/prestudentstatus/prestudentstatus', array("prestudent_id" => $data["prestudent_id"],"studiengang_kz"=>$data["studiengang_kz"], 'json')))
        {
            $this->result = $restquery;
            return true;
        }
        
        return false;
    }

    public function savePrestudentStatus($data)
    {
	if ($restquery = $this->rest->post('crm/prestudentstatus/prestudentstatus', $data))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	{
	    return false;
	}
    }
}
