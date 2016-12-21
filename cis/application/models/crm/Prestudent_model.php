<?php

/**
 * 
 */
class Prestudent_model extends REST_Model
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
	public function getPrestudent($prestudent_id)
	{
		return $this->load('crm/Prestudent/Prestudent', array('prestudent_id' => $prestudent_id));
	}
	
	/**
	 * 
	 */
	public function getPrestudentByPersonId()
	{
		return $this->load('crm/Prestudent/prestudentByPersonId', array('person_id' => $this->getPersonId()));
	}
	
	/**
	 * 
	 */
	public function getSpecialization($prestudent_id, $titel)
	{
		return $this->load('crm/Prestudent/Specialization', array('prestudent_id' => $prestudent_id, '$titel' => $titel));
	}
	
	/**
	 * 
	 */
	public function savePrestudent($parameters)
	{
		return $this->save('crm/Prestudent/Prestudent', $parameters);
	}
	
	/**
	 * 
	 */
	public function saveSpecialization($parameters)
	{
		return $this->save('crm/Prestudent/Specialization', $parameters);
	}
	
	/**
	 * 
	 */
	public function registerToReihungstest($parameters)
	{
		return $this->save('crm/Prestudent/AddReihungstest', $parameters);
	}
	
	/**
	 * 
	 */
	public function removePrestudent($parameters)
	{
		return $this->delete('crm/Prestudent/Prestudent', $parameters);
	}
	
	/**
	 * 
	 */
	public function removeSpecialization($parameters)
	{
		return $this->delete('crm/Prestudent/RmSpecialization', $parameters);
	}
	
	/**
	 * 
	 */
	public function removeRegistrationToReihungstest($parameters)
	{
		return $this->delete('crm/Prestudent/DelReihungstest', $parameters);
	}
}