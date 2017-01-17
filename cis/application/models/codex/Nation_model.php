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
			$nationsArray = array('null' => '');
			
			if (isSuccess($nations))
			{
				foreach($nations->retval as $nation)
				{
					$nationsArray[$nation->nation_code] = $nation->kurztext;
				}
				
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
}