<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *    http://example.com/index.php/welcome
     *  - or -
     *    http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('person_model');
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('prestudentStatus_model', "PrestudentStatusModel");
        $this->load->model('studiensemester_model', "StudiensemesterModel");
        $this->load->model('studienplan_model', "StudienplanModel");
	$this->load->model('userAuth_model', "UserAuthModel");
	$this->load->model('benutzer_model', "BenutzerModel");
    }

    public function index()
    {    
        if(isset($this->input->get()["studiengang_kz"]))
        {
            $this->_data["studiengang_kz"] = $this->input->get()["studiengang_kz"];
        }
        
        $this->_data['sprache'] = $this->get_language();
	$this->_loadLanguage($this->_data["sprache"]);
        $this->_data['studiengang_kz'] = $this->input->get('studiengang_kz');
        if ($this->_data['studiengang_kz'])
        {
            $this->session->set_userdata("studiengang_kz", $this->_data['studiengang_kz']);
        }
        $this->_data['username'] = $this->input->post('username');
        $this->_data['password'] = $this->input->post('password');
        $this->_data['email'] = $this->input->post('email');
        // First _POST then _GET
        $this->_data['code'] = $this->input->post('code') ? $this->input->post('code') : $this->input->get('code');

        if ($this->_data['code'])
	{
            $this->code_login($this->_data['code'], $this->_data, $this->_data["email"]);
	}
        elseif ($this->_data['username'] && $this->_data["password"])
	{
	    $this->_cisLogin($this->_data['username'], $this->_data["password"]);
        } else {
            // $this->load->view('templates/header');
            $this->load->view('login', $this->_data);
            // $this->load->view('templates/footer');
        }
    }

    private function code_login($code, &$data, $email = null)
    {
        $this->StudiensemesterModel->getNextStudiensemester("WS");
	var_dump($this->StudiensemesterModel);
        $this->session->set_userdata("studiensemester_kurzbz", $this->StudiensemesterModel->result->retval[0]->studiensemester_kurzbz);   
        
        $this->person_model->getPersonFromCode($code, $email);
        if(($this->person_model->result->error == 0) && (count($this->person_model->result->retval) == 1))
        {
            $data['person'] = $this->person_model->result->retval[0];
            if (isset($data['person']->person_id)) {
                $this->session->set_userdata("person_id", $data['person']->person_id);
                
                //load preinteressent data
                $this->_loadPrestudent();
                
                $this->_data["studiengaenge"] = array();
                foreach($this->_data["prestudent"] as $prestudent)
                {
                    //load studiengaenge der prestudenten
                    $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
                    $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
                    $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
                    $studiengang->studienplan = $studienplan;
                    array_push($this->_data["studiengaenge"], $studiengang);
                }
                
                if(isset($this->_data["studiengang_kz"]))
                {
                    $this->session->set_userdata("studiengang_kz", $this->_data["studiengang_kz"]);
                    redirect('/Bewerbung/'.$this->_data["studiengang_kz"]);
                }
                else if(count($this->_data["studiengaenge"]) > 0)
                {
                    redirect('/Bewerbung');
                }
                else
                {
                    redirect('/Studiengaenge');
                }
            } else {
                //TODO error
            }
        }
	else
	{
	    //TODO person not found
	    var_dump($this->person_model->result);
	}
    }
    
    private function _cisLogin($username, $password)
    {
	$this->UserAuthModel->checkByUsernamePassword(array("username"=>$username, "password"=>$password));
	if($this->UserAuthModel->result->retval == TRUE)
	{
	    $this->BenutzerModel->getBenutzer($username);
	    if($this->BenutzerModel->result->error == 0)
	    {
		$this->StudiensemesterModel->getNextStudiensemester("WS");
		$this->session->set_userdata("studiensemester_kurzbz", $this->StudiensemesterModel->result->retval[0]->studiensemester_kurzbz);
		
		$this->person_model->getPersonen($this->BenutzerModel->result->retval[0]->person_id);
		if(($this->person_model->result->error == 0) && (count($this->person_model->result->retval) == 1))
		{
		    $data['person'] = $this->person_model->result->retval[0];
		    if (isset($data['person']->person_id)) {
			$this->session->set_userdata("person_id", $data['person']->person_id);

			//load preinteressent data
			$this->_loadPrestudent();

			$this->_data["studiengaenge"] = array();
			foreach($this->_data["prestudent"] as $prestudent)
			{
			    //load studiengaenge der prestudenten
			    $studiengang = $this->_loadStudiengang($prestudent->studiengang_kz);
			    $prestudent->prestudentStatus = $this->_loadPrestudentStatus($prestudent->prestudent_id);
			    $studienplan = $this->_loadStudienplan($prestudent->prestudentStatus->studienplan_id);
			    $studiengang->studienplan = $studienplan;
			    array_push($this->_data["studiengaenge"], $studiengang);
			}

			if(isset($this->_data["studiengang_kz"]))
			{
			    $this->session->set_userdata("studiengang_kz", $this->_data["studiengang_kz"]);
			    redirect('/Bewerbung/'.$this->_data["studiengang_kz"]);
			}
			else if(count($this->_data["studiengaenge"]) > 0)
			{
			    redirect('/Bewerbung');
			}
			else
			{
			    redirect('/Studiengaenge');
			}
		    } else {
			//TODO error
		    }
		}
		else
		{
		    echo "person not found";
		}
	    }
	    else
	    {
		//TODO user not found
		echo "user not found";
	    }
	}
	else
	{
	    echo "auth failed";
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
    
    private function _loadStudiengang($stgkz = null)
    {
        if(is_null($stgkz))
        {
            $stgkz = $this->_data["prestudent"][0]->studiengang_kz;
        }
        if($this->StudiengangModel->getStudiengang($stgkz))
        {
            if(($this->StudiengangModel->result->error == 0) && (count($this->StudiengangModel->result->retval) == 1))
            {
                return $this->StudiengangModel->result->retval[0];
            }
            else
            {
                //TODO Daten konnten nicht geladen werden
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
}
