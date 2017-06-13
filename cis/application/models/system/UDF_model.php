<?php

/**
 * 
 */
class UDF_model extends REST_Model
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
	public function getUDF($schema = null, $table = null, $authNotRequired = false)
	{
	    $data = array();
        if($schema != null)
        {
            $data["schema"] = $schema;
        }

        if($table != null)
        {
            $data["table"] = $table;
        }

		$udf = $this->load(
			'system/UDF/UDF',
			$data,
			'UDF.getUDF',
			$authNotRequired
		);
		
		return $udf;
	}
	
	/**
	 * 
	 */
// 	public function getPhrase($phraseToSearchFor)
// 	{
// 		$phrases = $this->session->{'Phrase.getPhrasen'};
// 		
// 		if (hasData($phrases))
// 		{
// 			foreach ($phrases->retval as $phrase)
// 			{
// 				if ($phrase->phrase == $phraseToSearchFor)
// 				{
// 					return $phrase->text;
// 				}
// 			}
// 		}
// 	}
}