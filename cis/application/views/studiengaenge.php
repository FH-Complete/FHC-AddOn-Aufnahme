<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'studiengaenge'), $language);
?>

<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Studiengänge'));
//    $this->load->view('language');
    ?>

    <?php $this->load_views('view_studiengaenge'); ?>

    <?php
    $this->load->view('templates/footer');
    