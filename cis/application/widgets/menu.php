<?php

class Menu extends Widget {

    public function display($data) {
        if (is_null($this->config->item('menu'))) {
            $menu['items'] = array(
                array('href' => 'Overview', 'name' => 'Studiengänge', "glyphicon" => (isset($data["aktiv"]) && $data["atkiv"] == "Studiengänge") ? "glyphicon-ok" : "" ),
                array('href' => 'Person', 'name' => 'Bewerbung', 'glyphicon' => 'glyphicon-ok'),
                array('href' => 'Aufnahmetermine', 'name' => 'Aufnahmetermine'),
                array('href' => 'Messages', 'name' => 'Nachrichten'),
                array('href' => 'Documents', 'name' => 'Downloads'),
                array('href' => 'Logout', 'name' => 'Logout', 'glyphicon' => 'glyphicon-log-out')
            );
        }
	else
	{
            $menu['items'] = $this->config->item('menu');
	    $menu['data'] = $data;
	}
        
        $this->view('widgets/menu', $menu);
    }

}
