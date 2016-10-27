<?php
/**
 * ./cis/application/views/registration.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'login'), $sprache);
$this->load->view('templates/cookieHeader');
$this->load->view('templates/metaHeader');

?>

<div class="container">
    <?php $this->load->view('templates/iconHeader', array("header" => $this->getPhrase("Registration/Header", $sprache, $this->config->item('root_oe')))); ?>
    <div class="row">
	<?php $this->load_views('view_registration'); ?>
    </div>
</div>

<?php
$this->load->view('templates/footer');
