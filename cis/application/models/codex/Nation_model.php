<?php

/**
 * 
 */
class Nation_model extends REST_Model
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 */
	public function getAll()
	{
		$nations = $this->load('codex/Nation/All', null, 'Nation.getAll');
		
		if (!is_array($nations))
		{
			$nationsArray = array('null' => 'keine Auswahl');
			
			if (isSuccess($nations))
			{
                $nation_code = "";
                $kurztext = "";
                foreach ($nations->retval as $nation)
                {
                    if ($nation->nation_code == "A")
                    {
                        $nation_code = $nation->nation_code;
                        $kurztext = $nation->kurztext;
                    }
                    else
                    {
                        $nationsArray[$nation->nation_code] = $nation->kurztext;
                    }
                }
                $nationsArray = array_merge(array("null" => "", $nation_code => $kurztext), $nationsArray);
				
				if (count($nationsArray) > 0)
				{
					$this->storeSession('Nation.getAll', $nationsArray);
				}
			}
		}
		else
		{
			$nationsArray = $nations;
		}
		
		return success($nationsArray);
	}
	
	public function getNation($nation_code)
	{
		$result = $nations = $this->load('codex/Nation/All', null, 'Nation.getNation');
		if (isSuccess($nations))
		{
			$result = null;
			foreach($nations->retval as $nation)
			{
				if ($nation->nation_code == $nation_code)
				{
					$result = $nation;
					$this->storeSession('Nation.getNation', $result);
					break;
				}
			}
		}
		
		return $result;
	}
}