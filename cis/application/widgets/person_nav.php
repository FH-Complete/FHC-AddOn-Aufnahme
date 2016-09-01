<?php
/**
 * ./cis/application/widgets/person_nav.php
 *
 * @package default
 */


class Person_nav extends Widget {

	/**
	 *
	 * @param unknown $data
	 */
	public function display($data) {

		if (!isset($data['items'])) {
			$data['items'] = array(
				array('href'=>site_url('/Bewerbung'), 'name'=>'Persönliche Daten', "id"=>"personalData"),
				array('href'=>site_url('/Requirements'), 'name'=>'Zugangsvoraussetzung', "id"=>"requirements"),
				array('href'=>site_url('/Summary'), 'name'=>'Zusammenfassung', "id"=>"summary"),
				array('href'=>site_url('/Send'), 'name'=>'Absenden', "id"=>"send")
			);
		}

		$this->view('widgets/navigation', $data);
	}


}
