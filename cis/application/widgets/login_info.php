<?php

class Login_info extends Widget {

    public function display($name) {
	if(isset($name))
	{
	    $this->view('widgets/login_info');
	} 
    }
}
