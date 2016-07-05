<?php $this->lang->load(array('registration'), $sprache); ?>

<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Registration</li>
    </ol>
    <?php echo form_open("Registration/resendCode", array("id" => "ResendCodeForm", "name" => "ResendCodeForm", "class" => "form-horizontal")); ?>
    <img style="width:150px;" class="center-block img-responsive" src="<?php echo APP_ROOT . 'skin/styles/' . $this->config->item('theme'); ?>/logo.png">

    <div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
	<?php echo form_label("E-Mail Adresse", "email", array("name" => "email", "for" => "email", "class" => "col-sm-3 control-label")) ?>
	<div class="col-sm-4">
	    <?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength' => 128, "type" => "email", "class" => "form-control")); ?>
	    <?php echo form_error("email"); ?>
	</div>
    </div>
    <div class="form-group">
	<div class="col-sm-4 col-sm-offset-3">
	    <?php echo form_submit(array("value" => "Abschicken", "name" => "submit_btn", "class" => "btn btn-primary")); ?>
	</div>
    </div>
    <?php
    echo form_close();
//wirtes message if email adress exists
    echo (isset($message)) ? $message : "";
    if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
    ?>
