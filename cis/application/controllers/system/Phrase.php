<?php

/**
 * 
 */
class Phrase extends JSON_Controller
{
	/**
	 * 
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Load model PhraseModel
		$this->load->model('system/Phrase_model', 'PhraseModel');
	}
	
	/**
	 * 
	 */
	public function getPhrasen($parameters)
	{
		$this->response($this->PhraseModel->getPhrasen($parameters));
	}
	
	/**
	 * 
	 */
	public function getPhrase($phraseToSearchFor)
	{
		$this->response($this->PhraseModel->getPhrase($phraseToSearchFor));
	}
}