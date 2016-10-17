<?php
/**
 * ./cis/application/core/MY_Loader.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

class MY_Loader extends CI_Loader {


	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 *
	 * @param unknown $view
	 * @param unknown $data (optional)
	 */
	function load_views($view, $data = array()) {
		if (!is_null($this->config->item($view))) {
			foreach ($this->config->item($view) as $v)
				$this->load->view($v, $data);
		}
	}


	/**
	 *
	 * @param unknown $phrase
	 * @param unknown $sprache
	 * @param unknown $oe_kurzbz      (optional)
	 * @param unknown $orgform_kurzbz (optional)
	 * @return unknown
	 */
	function getPhrase($phrase, $sprache, $oe_kurzbz = null, $orgform_kurzbz = null) {
		if (isset($this->session->userdata()["phrasen"])) {
			$phrasen = $this->session->userdata()["phrasen"];
			if (is_array($phrasen)) {
				$text = "";
				$sprache = ucfirst($sprache);
				foreach ($phrasen as $p) {
					if (($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == $orgform_kurzbz) && ($p->sprache == $sprache))
					{
						if ($this->config->item('display_phrase_name'))
							$text = $p->text . " <i>[$p->phrase]</i>";
						else
							$text = $p->text;
					}
					elseif (($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == $oe_kurzbz) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
					{
						if ($this->config->item('display_phrase_name'))
							$text = $p->text . " <i>[$p->phrase]</i>";
						else
							$text = $p->text;
					}
					elseif (($p->phrase == $phrase) && ($p->orgeinheit_kurzbz == null) && ($p->orgform_kurzbz == null) && ($p->sprache == $sprache))
					{
						if ($this->config->item('display_phrase_name'))
							$text = $p->text . " <i>[$p->phrase]</i>";
						else
							$text = $p->text;
					}
				}

				if($text != "")
					return $text;
				
				if ($this->config->item('display_phrase_name'))
					return "<i>[$phrase]</i>";
			}
			else {
				return $phrasen;
			}
		}
		else {
			return "please load phrases first";
		}
	}
}