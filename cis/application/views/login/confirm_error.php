<?php
/**
 * ./cis/application/views/login/confirm_error.php
 *
 * @package default
 */


$this->lang->load(array('registration'), $sprache); ?>

<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Registration</li>
    </ol>

    <?php
echo (isset($message)) ? $message : "";
?>
</div>
