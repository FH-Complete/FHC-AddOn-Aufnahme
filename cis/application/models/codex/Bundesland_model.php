<?php

/**
 * 
 */
class Bundesland_model extends REST_Model
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
		$bundeslaender = $this->load('codex/Bundesland/All', null, 'Bundesland.getBundeslaender');
		$bundeslaenderArray = array();
		
		if (isSuccess($bundeslaender))
		{
			foreach($bundeslaender->retval as $bundesland)
			{
				$bundeslaenderArray[$bundesland->bundesland_code] = $bundesland->bezeichnung;
			}
			
			if (count($bundeslaenderArray) > 0)
			{
				$this->storeSession('Bundesland.getBundeslaender', $bundeslaenderArray);
			}
		}
		
		return success($bundeslaenderArray);
	}
	
	public function getBundesland($code)
	{
		$result = $bundeslaender = $this->load('codex/Bundesland/All', null, 'Bundesland.getBundesland');
		
		if (isSuccess($bundeslaender))
		{
			foreach($bundeslaender->retval as $bundesland)
			{
				if ($bundesland->bundesland_code == $code)
				{
					$result = $bundesland;
					$this->storeSession('Bundesland.getBundesland', $result);
					break;
				}
			}
		}
		
		return $result;
	}
}