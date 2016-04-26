<?php

class Menu extends Widget 
{
    public function display($data) 
	{
        if (is_null($this->config->item('menu')))
		{
            $menu['items'] = array(
				array('href' =>'Overview', 'name' => 'Allgemein'),
				array('href' =>'Person', 'name' => 'Perönliche Daten', 'glyphicon' => 'glyphicon-ok'),
				array('href' =>'Contact', 'name' => 'Kontakt'),
				array('href' =>'Admittance', 'name' => 'ZGV'),
				array('href' =>'Documents', 'name' => 'Dokumente'),
				array('href' =>'Logout', 'name' => 'Logout', 'glyphicon' => 'glyphicon-log-out')
			);
        }
		else
			$menu['items'] = $this->config->item('menu');

		//foreach ()
		

		$this->view('widgets/menu', $menu);
    }
    
}
