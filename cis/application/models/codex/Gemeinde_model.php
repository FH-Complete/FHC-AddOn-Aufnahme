<?php
/**
 * ./cis/application/models/Gemeinde_model.php
 *
 * @package default
 */

class Gemeinde_model extends REST_Model
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->sessionName = 'gemeinde';
	}

	public function getGemeinde()
	{
		return $this->load('codex/gemeinde/Gemeinde', null, true);
	}
	
	/**
	 *
	 * @return unknown
	 */
	public function getGemeindeByPlz($plz)
	{
		return $this->load('codex/Gemeinde/GemeindeByPlz', array('plz' => $plz));
	}
}