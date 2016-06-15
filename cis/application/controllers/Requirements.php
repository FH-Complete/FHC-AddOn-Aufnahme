<?php

class Requirements extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->lang->load('requirements', $this->get_language());
        $this->load->helper("form");
        $this->load->library("form_validation");
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        $this->load->model('studienplan_model', "StudienplanModel");
	$this->load->model('dms_model', "DmsModel");
        $this->load->model('akte_model', "AkteModel");
	$this->load->model('person_model', "PersonModel");
    }

    public function index()
    {
        $this->checkLogin();
        $this->_data['sprache'] = $this->get_language();
	
	//load person data
        $this->_loadPerson();
        
        //load studiengang
        $this->_loadStudiengang($this->input->get()["studiengang_kz"]);
        
        //load preinteressent data
        $this->_loadPrestudent();
	
	//load dokumente
        $this->_loadDokumente($this->session->userdata()["person_id"]);
        
        //load prestudent data for correct studiengang
        foreach($this->_data["prestudent"] as $prestudent)
        {
            //load studiengaenge der prestudenten
            if($prestudent->studiengang_kz == $this->input->get()["studiengang_kz"])
            {
                $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);         
                $this->_data["studiengang"]->studienplan = $studienplan;
            } 
        }
	
	$files = $_FILES;
	if(count($files) > 0)
	{
	    foreach($files as $key=>$file)
	    {
		if(is_uploaded_file($file["tmp_name"]))
		{
		    $obj = new stdClass();
		    $obj->version = 0;
		    $obj->mimetype = $file["type"];
		    $obj->name = $file["name"];
		    $obj->oe_kurzbz = null;

		    switch($key)
		    {
			case "maturazeugnis":
			    $obj->dokument_kurzbz = "Maturaze";
			    break;                        
			default:
			    $obj->dokument_kurzbz = "Sonst";
			    break;
		    }

		    foreach($this->_data["dokumente"] as $akte)
		    {
			if(($akte->dokument_kurzbz == $obj->dokument_kurzbz) && ($akte->dms_id != null) && ($obj->dokument_kurzbz != "Sonst"))
			{
			    $dms = $this->_loadDms($akte->dms_id);
			    $obj->version = $dms->version+1;
			}
		    }

		    $obj->kategorie_kurzbz = "Akte";

		    $type = pathinfo($file["name"], PATHINFO_EXTENSION);
		    $data = file_get_contents($file["tmp_name"]);
		    $obj->file_content = 'data:image/' . $type . ';base64,' . base64_encode($data);

		    $this->DmsModel->saveDms($obj);

		    if($this->DmsModel->result->error == 0)
		    {
			$akte = new stdClass();
			$akte->dms_id = $this->DmsModel->result->retval;
			$akte->person_id = $this->_data["person"]->person_id;
			$akte->mimetype = $file["type"];

			$akte->bezeichnung = mb_substr($obj->name, 0, 32);
			$akte->dokument_kurzbz = $obj->dokument_kurzbz;
			$akte->titel = $key;
			$akte->insertvon = 'online';

			$this->AkteModel->saveAkte($akte);
		    }

		    if(unlink($file["tmp_name"]))
		    {
			//removing tmp file successful
		    }
		}

	    }
	}
	
        
        $this->load->view('requirements', $this->_data);
    }
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["preinteressent"]->studiengang_kz;
        }
        if($this->StudiengangModel->getStudiengang($stgkz))
        {
            if(($this->StudiengangModel->result->error == 0) && (count($this->StudiengangModel->result->retval) == 1))
            {
                $this->_data["studiengang"] = $this->StudiengangModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
            }
        }
    }
    
    private function _loadPrestudent()
    {
        if($this->PrestudentModel->getPrestudent(array("person_id"=>$this->session->userdata()["person_id"])))
        {
            if($this->PrestudentModel->result->error == 0)
            {
                $this->_data["prestudent"] = $this->PrestudentModel->result->retval;        
            }
        }
    }
    
    private function _loadPrestudentStatus($prestudent_id)
    {
        if($this->PrestudentStatusModel->getPrestudentStatus(array("prestudent_id"=>$prestudent_id, "studiensemester_kurzbz"=>$this->session->userdata()["studiensemester_kurzbz"], "ausbildungssemester"=>1, "status_kurzbz"=>"Interessent")))
        {
            if(($this->PrestudentStatusModel->result->error == 0) && (count($this->PrestudentStatusModel->result->retval) == 1))
            {
                return $this->PrestudentStatusModel->result->retval[0];
            }
        }
    }
    
    private function _loadStudienplan($studienplan_id)
    {
        if($this->StudienplanModel->getStudienplan($studienplan_id))
        {
            if(($this->StudienplanModel->result->error == 0) && (count($this->StudienplanModel->result->retval) == 1))
            {
                return $this->StudienplanModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
            }
        }
    }
    
    private function _loadDokumente($person_id, $dokumenttyp_kurzbz=null)
    {
        $this->_data["dokumente"] = array();
        $this->AkteModel->getAkten($person_id, $dokumenttyp_kurzbz);
        
        if($this->AkteModel->result->error == 0)
        {
            foreach($this->AkteModel->result->retval as $akte)
            {
                $this->_data["dokumente"][$akte->dokument_kurzbz] = $akte;
            }
        }
    }
    
    private function _loadPerson()
    {
        if($this->PersonModel->getPersonen(array("person_id"=>$this->session->userdata()["person_id"])))
        {
            if(($this->PersonModel->result->error == 0) && (count($this->PersonModel->result->retval) == 1))
            {
                $this->_data["person"] = $this->PersonModel->result->retval[0];
            }
        }
    }
    
}
