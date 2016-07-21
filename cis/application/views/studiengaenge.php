<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'studiengaenge'), $language);
?>

<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Studiengänge'));
    if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
    ?>
    <div class="stg_container">
	<?php $this->load_views('view_studiengaenge'); ?>
    </div>
    <?php
    $this->load->view('templates/footer');
    ?>
</div>
    