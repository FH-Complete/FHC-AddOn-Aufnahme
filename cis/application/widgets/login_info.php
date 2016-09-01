<?php
/**
 * ./cis/application/widgets/login_info.php
 *
 * @package default
 */


class Login_info extends Widget {

	/**
	 *
	 * @param unknown $name
	 */
	public function display($name) {
		if (isset($name)) {
			$this->view('widgets/login_info');
		}
	}


}
