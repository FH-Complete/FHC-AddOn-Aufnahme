<?php
/**
 * ./cis/application/core/MY_Controller.php
 *
 * @package default
 */

defined('BASEPATH') or exit('No direct script access allowed');

class JSON_Controller extends CI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Loading the addon configuration
		$this->load->config('aufnahme');
	}
	
	protected function response($data)
	{
		$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data));
	}
}