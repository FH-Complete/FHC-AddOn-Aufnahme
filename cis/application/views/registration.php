<?php
/**
 * ./cis/application/views/registration.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'login'), $sprache);
?>

<div class="container">
    <?php $this->load->view('templates/iconHeader'); ?>
    <div class="row">
	<?php $this->load_views('view_registration'); ?>
    </div>
</div>

<?php
$this->load->view('templates/footer');
