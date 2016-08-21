<?php

class CheckSystem extends MY_Controller
{

	private function __isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

    public function index()
    {
		//$this->output->enable_profiler(TRUE);
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

		// =============== View Performance Test =====================0

		$this->load->view('checksystem',$data);
    }

}

