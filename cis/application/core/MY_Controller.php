<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $_data = array();

    public function __construct() {
        parent::__construct();
        $this->load->config('aufnahme.dist');
        $this->load->config('aufnahme', FALSE, TRUE);
        $this->load->helper('url');
        $this->load->library('session');
        //$this->load->spark('restclient/2.1.0');
        $this->_data['language'] = $this->get_language();
    }

    public function get_language() {
        if (is_null($this->input->get('language'))) {
            if (is_null($this->session->sprache)) {
                return $this->config->item('default_language');
            } else
                return $this->session->language;
        }
        else {
            $this->session->language = $this->input->get('language');
            return $this->input->get('language');
        }
    }
    
    public function checkLogin()
    {
        if(is_null($this->session->person_id))
        {
            redirect("/Login");
        }
    }

}
