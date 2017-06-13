<?php
/**
 * ./cis/application/widgets/userdefinedfield.php
 *
 * @package default
 */

class userdefinedfield extends Widget
{
	/**
	 *
	 * @param unknown $data
	 */
	public function display($data)
	{
	    switch($data["data"]->type)
        {
            case 'multipledropdown':
                $this->view('widgets/dropdown', $data);
                break;
            case 'dropdown':
                $this->view('widgets/dropdown', $data);
                break;
            case 'textfield':
                $this->view('widgets/text', $data);
                break;
            case 'textarea':
                $this->view('widgets/textarea', $data);
                break;
            case 'checkbox':
                $this->view('widgets/checkbox', $data);
                break;

        }

	}
}