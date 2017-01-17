<?php

/**
 * 
 */
class Phrase_model extends REST_Model
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
	public function getPhrasen($app, $sprache, $authNotRequired = false)
	{
		$phrasen = $this->load(
			'system/Phrase/Phrases',
			array(
				'app' => $app,
				'sprache' => $sprache
			),
			'Phrase.getPhrasen',
			$authNotRequired
		);
		
		return $phrasen;
	}
	
	/**
	 * 
	 */
	public function getPhrase($phraseToSearchFor)
	{
		$phrases = $this->session->{'Phrase.getPhrasen'};
		
		if (hasData($phrases))
		{
			foreach ($phrases->retval as $phrase)
			{
				if ($phrase->phrase == $phraseToSearchFor)
				{
					return $phrase->text;
				}
			}
		}
	}
}