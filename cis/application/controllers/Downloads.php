<?php

/**
 * ./cis/application/controllers/Downloads.php
 *
 * @package default
 */
class Downloads extends MY_Controller
{
    private $_person_id;
    private $_studiensemester_kurzbz;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('prestudent_model', "PrestudentModel");
        $this->load->model('studiengang_model', "StudiengangModel");
        $this->load->model('person_model', 'PersonModel');
        $this->_data["sprache"] = $this->get_language();
        $this->_data["numberOfUnreadMessages"] = $this->_getNumberOfUnreadMessages();
    }


    /**
     *
     */
    public function index()
    {
        $this->checkLogin();

        $person = null;
        if (isset($this->session->{'Person.getPerson'}))
        {
            $person = $this->session->{'Person.getPerson'};
            if (hasData($person))
            {
                if (isset($person->retval->person_id) && is_numeric($person->retval->person_id))
                {
                    $this->_person_id = $person->retval->person_id;
                }
            }
        }

        $this->_loadData();

        $this->_loadLanguage($this->_data["sprache"]);

        $this->load->view('downloads', $this->_data);
    }


    /**
     *
     */
    private function _loadData()
    {
        //load person data
        $this->_data["person"] = $this->_loadPerson();

        $this->_data["prestudent"] = $this->_loadPrestudent($this->_person_id);

        $this->_data["studiengaenge"] = array();
        foreach ($this->_data["prestudent"] as $prestudent)
        {
            if ($prestudent->studiengang_kz != null)
            {
                $studiengang = $this->_loadStudiengnag($prestudent->studiengang_kz);
                $this->_data["studiengaenge"][$studiengang->oe_kurzbz] = $studiengang->bezeichnung;
            }
        }
    }


    /**
     *
     * @param unknown $person_id
     * @return unknown
     */
    private function _loadPrestudent($person_id)
    {
        $this->PrestudentModel->getPrestudent(array("person_id" => $person_id));
        if ($this->PrestudentModel->isResultValid() === true)
        {
            return $this->PrestudentModel->result->retval;
        }
        else
        {
            $this->_setError(true, $this->PrestudentModel->getErrorMessage());
        }
    }


    private function _loadStudiengnag($studiengang_kz)
    {
        $this->StudiengangModel->getStudiengang($studiengang_kz);
        if ($this->StudiengangModel->isResultValid() === true)
        {
            return $this->StudiengangModel->result->retval[0];
        }
        else
        {
            $this->_setError(true, $this->StudiengangModel->getErrorMessage());
        }
    }


    private function _loadPerson()
    {
        $this->PersonModel->getPersonen(array("person_id" => $this->_person_id));
        if ($this->PersonModel->isResultValid() === true)
        {
            if (count($this->PersonModel->result->retval) == 1)
            {
                return $this->PersonModel->result->retval[0];
            }
            else
            {
                return $this->PersonModel->result->retval;
            }
        }
        else
        {
            $this->_setError(true, $this->PersonModel->getErrorMessage());
        }
    }


}
