<?php

class CheckSystem extends MY_Controller
{
	private $__time;

	public function __construct()
	{
		parent::__construct();
		$this->benchmark->mark('code_start');
		$this->load->model('studiengang_model', "StudiengangModel");
		$this->load->model('studienplan_model', "StudienplanModel");
		$this->load->model('studiensemester_model', 'StudiensemesterModel');
        $this->load->model('organisationsform_model', 'OrgformModel');
		$this->load->model('person_model', 'PersonModel');
		$this->load->model('Bewerbungstermine_model', 'BewerbungstermineModel');
		$this->load->model('reihungstest_model', "ReihungstestModel");
        $this->lang->load('studiengaenge', $this->get_language());
		$this->benchmark->mark('code_end');
		$this->__time = $this->benchmark->elapsed_time('code_start', 'code_end');
    }

	private function __isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

    public function index()
    {
		//$this->output->enable_profiler(TRUE);
		$data['constructorTime'] = $this->__time;

		// ========= API-Test =================
		$this->benchmark->mark('code_start');
		$apitest = $this->rest->get('person/person/person', array("person_id" => 1, 'json'));
		$data['apitest'] = new stdClass();
        if (!isset($apitest->error))
		{
			$data['apitest']->error = true;
			$data['apitest']->value = 'REST-Server-Connection-Error: '.print_r($apitest,true);
		}
		elseif ($apitest->error==1)
		{
			$data['apitest']->error = true;
			$data['apitest']->value = 'Error: '.$apitest->retval;
		}
		elseif ($apitest->error==0)
		{
			if (!is_array($apitest->retval))
			{
				$data['apitest']->error = true;
				$data['apitest']->value = 'Error: Returnvalue is not a valid Array!';
			}
			elseif (!is_object($apitest->retval[0]))
			{
				$data['apitest']->error = true;
				//var_dump($apitest->retval[0]);
				$data['apitest']->value = 'Error: Returnvalue is not a valid Object!';
			}
			else
			{
				$data['apitest']->error = false;
				$data['apitest']->value = 'OK'.print_r($apitest->retval, true);
			}
			
		}
		$this->benchmark->mark('code_end');
		$data['apitest']->time = $this->benchmark->elapsed_time('code_start', 'code_end');

		// =============== Performance Tests =====================
		// ToDo

		// == View header ==
		$this->benchmark->mark('code_start');
		$data['perfViewHeader'] = new stdClass();
		$this->load->view('templates/header','',false);
		$this->benchmark->mark('code_end');
		$data['perfViewHeader']->time = $this->benchmark->elapsed_time('code_start', 'code_end');

		// ================ Output ===============================
		$this->load->view('checksystem',$data);
    }

}

