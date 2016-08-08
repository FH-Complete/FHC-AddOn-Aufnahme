<?php

class Phrase_model extends MY_Model
{

    public function __construct()
    {
	parent::__construct();
    }

    public function getPhrasen($sprache, $oe_kurzbz = null, $orgform_kurzbz = null, $phrase= null)
    {
	if ($restquery = $this->rest->get('system/phrase/phrases', array("app"=>"aufnahme", "sprache"=>$sprache, "phrase"=>$phrase, "orgeinheit_kurzbz"=>$oe_kurzbz, "orgform_kurzbz"=>$orgform_kurzbz, "blockTags"=>"no")))
	{
	    $this->result = $restquery;
	    return true;
	}
	else
	    return false;
    }
    
    public function getLoadedPhrase($phrase)
    {
	if((!is_null($this->result)) && (!empty($this->result->retval)))
	{
	    foreach($this->result->retval as $p)
	    {
		if($p->phrase == $phrase)
		{
		    return $p->text;
		}
	    }
	}
    }
}
