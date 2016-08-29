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
		// == Controller Studiengaenge Method Index() ==
		$this->benchmark->mark('code_start');
		$data['studiengaenge'] = new stdClass();
		//load person data
		$this->PersonModel->getPersonen(array("person_id"=>1));
        if($this->PersonModel->isResultValid() === true)
			$person = $this->PersonModel->result->retval[0];
	
        $this->OrgformModel->getAll();
        
        if($this->OrgformModel->result->error == 0)
            $orgform = $this->OrgformModel->result->retval;
        else
	    	$this->_setError(true, $this->OrgformModel->getErrorMessage());
        
	    $this->StudiensemesterModel->getNextStudiensemester('WS');
		//var_dump($this->StudiensemesterModel->result);
		$studiensemester = $this->StudiensemesterModel->result;
			
	    if(($studiensemester->error == 0) && (count($studiensemester->retval) > 0))
	    {
			$this->benchmark->mark('codepart_start');
			$studiensemester = $studiensemester->retval[0];
			$this->StudiengangModel->getStudiengangStudienplan($studiensemester->studiensemester_kurzbz, 1);
			$studiengaenge = $this->StudiengangModel->result;
			var_dump($studiengaenge);
			if( ($studiengaenge->error == 0) && is_array($studiengaenge->retval) )
			{
				$this->benchmark->mark('codepart_end');
				$studiengaenge = $studiengaenge->retval[0];
				$data['studiengaenge']->time1 = 'Time elapsed for Studiengaenge/index->getStudienplan: '.$this->benchmark->elapsed_time('codepart_start', 'codepart_end').'ms';

				$this->benchmark->mark('foreach_start');
				foreach($studiengaenge as $stg)
				{
					if($stg->onlinebewerbung === "t")
					{
						$this->benchmark->mark('codepart_start');
						$stg->fristen = $this->_getBewerbungstermine($stg->studiengang_kz, $data["studiensemester"]->studiensemester_kurzbz);
						//$stg->reihungstests = $this->_loadReihungstests($stg->studiengang_kz, $data["studiensemester"]->studiensemester_kurzbz);
						$this->benchmark->mark('codepart_end');
						log_message('debug', 'Time elapsed for Studiengaenge/index->Reihunstest/Termin: '.$this->benchmark->elapsed_time('codepart_start', 'codepart_end').'ms');

						if(isset($data["studiengang_kz"]) && ($stg->studiengang_kz === $data["studiengang_kz"]))
							if(count($stg->studienplaene) === 1)
								redirect("/Bewerbung/studiengang/".$stg->studiengang_kz."/".$stg->studienplaene[0]->studienplan_id);
					}
				}
				$this->benchmark->mark('foreach_end');
				$data['studiengaenge']->timeForeach = $this->benchmark->elapsed_time('foreach_start', 'foreach_end').'ms';

				//$this->load->view('studiengaenge', $data);
			}
			elseif (($studiengaenge->error == 0) && !is_array($studiengaenge->retval))
			{
				$data['studiengaenge']->msg = 'Keine Studiengaenge vorhanden!';
				
			}
			else
			{
				$data['studiengaenge']->msg = $studiengaenge->retval;
			}
		}
		else
		{
			$data['studiengaenge']->msg = $studiensemester->retval;
		}

	    $this->benchmark->mark('code_end');
	    $data['studiengaenge']->time = $this->benchmark->elapsed_time('code_start', 'code_end');

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

