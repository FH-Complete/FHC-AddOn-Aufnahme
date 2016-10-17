<?php
/**
 * ./cis/application/models/Phrase_model.php
 *
 * @package default
 */


class Phrase_model extends MY_Model
{

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $sprache
	 * @param unknown $oe_kurzbz      (optional)
	 * @param unknown $orgform_kurzbz (optional)
	 * @param unknown $phrase         (optional)
	 * @return unknown
	 */
	public function getPhrasen($sprache, $oe_kurzbz = null, $orgform_kurzbz = null, $phrase= null) {
		if ($restquery = $this->rest->get('system/phrase/phrases', array("app"=>"aufnahme", "sprache"=>$sprache, "phrase"=>$phrase, "orgeinheit_kurzbz"=>$oe_kurzbz, "orgform_kurzbz"=>$orgform_kurzbz, "blockTags"=>"yes"))) {
			$this->result = $restquery;
			return true;
		}
		else
			return false;
	}


	/**
	 *
	 * @param unknown $phrase
	 * @return unknown
	 */
	public function getLoadedPhrase($phrase) {
		if ((!is_null($this->result)) && (!empty($this->result->retval))) {
			foreach ($this->result->retval as $p) {
				if ($p->phrase == $phrase) {
					return $p->text;
				}
			}
		}
	}


}
