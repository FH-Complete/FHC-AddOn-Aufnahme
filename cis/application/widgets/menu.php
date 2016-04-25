<?php

class Menu extends Widget 
{

    public function display($data) 
	{
        
        if (!isset($data['items'])) 
		{
            $data['items'] = array(
				array('href' =>'Overview', 'name' => 'Allgemein'),
				array('href' =>'Person', 'name' => 'Perönliche Daten', 'glyphicon' => 'glyphicon-ok'),
				array('href' =>'Contact', 'name' => 'Kontakt'),
				array('href' =>'Admittance', 'name' => 'ZGV'),
				array('href' =>'Documents', 'name' => 'Dokumente'),
				array('href' =>'Logout', 'name' => 'Logout', 'glyphicon' => 'glyphicon-log-out')
			);
        }

        $this->view('widgets/menu', $data);
    }
    
}
