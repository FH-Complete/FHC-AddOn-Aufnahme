<?php

class Menu extends Widget {

    public function display($data) {
        if (is_null($this->config->item('menu'))) {
            $menu['items'] = array(
                array('href' => 'Overview', 'name' => 'Studiengänge'),
                array('href' => 'Person', 'name' => 'Bewerbung', 'glyphicon' => 'glyphicon-ok'),
                array('href' => 'Contact', 'name' => 'Aufnahmetermine'),
                array('href' => 'Admittance', 'name' => 'Nachrichten'),
                array('href' => 'Documents', 'name' => 'Downloads'),
                array('href' => 'Logout', 'name' => 'Logout', 'glyphicon' => 'glyphicon-log-out')
            );
        } else
            $menu['items'] = $this->config->item('menu');
        
        $this->view('widgets/menu', $menu);
    }

}
