<?php

class Navigation extends Widget 
{

    public function display($data) 
	{
        
        if (!isset($data['items'])) 
		{
            $data['items'] = array('Allgemein', 'Perönliche Daten', 'Kontakt');
        }

        $this->view('widgets/navigation', $data);
    }
    
}
