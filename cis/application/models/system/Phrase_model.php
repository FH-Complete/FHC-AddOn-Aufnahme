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
	public function getPhrasen($parameters)
	{
		return $this->load('system/Phrase/Phrases', $parameters, 'Phrase.getPhrasen');
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