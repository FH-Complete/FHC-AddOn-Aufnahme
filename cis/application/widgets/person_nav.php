<?php

class Person_nav extends Widget 
{

    public function display($data) 
	{
        
        if (!isset($data['items'])) 
		{
            $data['items'] = array('Allgemein', 'Persönliche Daten', 'Kontakt');
        }

        $this->view('widgets/navigation', $data);
    }
    
}
