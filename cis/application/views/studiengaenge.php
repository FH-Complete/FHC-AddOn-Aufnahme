<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'studiengaenge'), $language);
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
?>

<div class="container">
    <?php
    echo $this->template->widget("menu", array('aktiv' => 'Studiengaenge'));
//    $this->load->view('language');
    ?>

    <?php $this->load_views('view_studiengaenge'); ?>

    <?php
    $this->load->view('templates/footer');
    