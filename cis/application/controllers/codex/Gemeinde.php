<?php

/**
 * ./cis/application/controllers/Bewerbung.php
 *
 * @package default
 */
class Gemeinde extends JSON_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model GemeindeModel
		$this->load->model('codex/Gemeinde_model', 'GemeindeModel');
	}
	
	public function ort($plz)
	{
		$this->response($this->GemeindeModel->getGemeindeByPlz($plz));
	}
}