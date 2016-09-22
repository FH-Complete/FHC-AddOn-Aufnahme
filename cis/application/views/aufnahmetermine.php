<?php
/**
 * ./cis/application/views/aufnahmetermine.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'termine'), $sprache);
$this->load->view('templates/metaHeader');


if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Aufnahmetermine')); ?>
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_aufnahmetermine'); ?>
        </div>
    </div>
</div>

<?php
$this->load->view('templates/footer');
