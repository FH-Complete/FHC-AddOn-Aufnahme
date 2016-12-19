<?php

/**
 * 
 */
class Prestudentstatus_model extends REST_Model
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
	public function getPrestudentstatus($ausbildungssemester, $studiensemester_kurzbz, $status_kurzbz, $prestudent_id)
	{
		return $this->load(
			'crm/Prestudentstatus/Prestudentstatus',
			array(
				'ausbildungssemester' => $ausbildungssemester,
				'studiensemester_kurzbz' => $studiensemester_kurzbz,
				'status_kurzbz' => $status_kurzbz,
				'prestudent_id' => $prestudent_id
			)
		);
	}
	
	/**
	 * 
	 */
	public function getLastStatus($prestudent_id, $studiensemester_kurzbz = null, $status_kurzbz = null)
	{
		return $this->load(
			'crm/Prestudentstatus/Laststatus',
			array(
				'prestudent_id' => $prestudent_id,
				'studiensemester_kurzbz' => $studiensemester_kurzbz,
				'status_kurzbz' => $status_kurzbz
			)
		);
	}
	
	/**
	 * 
	 */
	public function savePrestudentstatus($parameters)
	{
		return $this->save('crm/Prestudentstatus/Prestudentstatus', $parameters);
	}
	
	/**
	 * 
	 */
	public function removePrestudentStatus($parameters)
	{
		return $this->delete('crm/Prestudentstatus/Prestudentstatus', $parameters);
	}
}