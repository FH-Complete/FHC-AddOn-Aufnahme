<?php

class Person_nav extends Widget {

    public function display($data) {

        if (!isset($data['items'])) {
            $data['items'] = array(
                array('href'=>site_url('/Bewerbung'), 'name'=>'Persönliche Daten'),
                array('href'=>site_url('/Requirements'), 'name'=>'Zugangsvoraussetzung'),
                array('href'=>site_url('/Summary'), 'name'=>'Zusammenfassung'),
                array('href'=>site_url('/Send'), 'name'=>'Absenden')
                );
        }

        $this->view('widgets/navigation', $data);
    }

}
