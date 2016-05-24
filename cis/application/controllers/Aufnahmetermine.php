<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aufnahmetermine extends MY_Controller {

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
        $this->lang->load('termine', $this->get_language());
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('studienplan_model', "StudienplanModel");
        $this->load->model('studiensemester_model', "StudiensemesterModel");
    }

    public function index() {
        $this->checkLogin();
        
        //load studiensemester
        $this->_loadNextStudiensemester();
        
        //load studiengaenge
        $this->_loadStudiengaenge();

        $this->_data["sprache"] = $this->get_language();
        $this->load->view('aufnahmetermine', $this->_data);
    }
    
    private function _loadStudiengaenge()
    {
        $this->StudiengangModel->getAll();
        
        if($this->StudiengangModel->result->error == 0)
        {
            foreach($this->StudiengangModel->result->retval as $key=>$studiengang)
            {
                $this->StudienplanModel->getStudienplaeneFromSem(array(
                            "studiengang_kz"=>$studiengang->studiengang_kz,
                            "studiensemester_kurzbz"=>$this->_data["studiensemester"]->studiensemester_kurzbz,
                            "ausbildungssemester"=>1
                    ));

                if($this->StudienplanModel->result->error == 0)
                {
                    $studiengang->studienplaene = $this->StudienplanModel->result->retval;
                }
            }
            $this->_data["studiengaenge"] = $this->StudiengangModel->result->retval;
        }
        else
        {
            //TODO fehler beim laden der daten
        }
    }
    
    private function _loadNextStudiensemester()
    {
        $this->StudiensemesterModel->getNextStudiensemester("WS");
        if($this->StudiensemesterModel->result->error == 0)
        {
            $this->_data["studiensemester"] = $this->StudiensemesterModel->result->retval[0];
        }
    }

}
