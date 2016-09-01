<?php
/**
 * ./cis/application/views/login/confirm_login.php
 *
 * @package default
 */


$this->lang->load(array('aufnahme'), $sprache); ?>

<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Registration</li>
    </ol>
    <?php echo form_open("Registration/code_login?studiengang_kz=".((isset($studiengang_kz)) ? $studiengang_kz : ""), array("id" => "ChangePasswordForm", "name" => "ChangePasswordForm", "class" => "form-horizontal")); ?>
    <img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>">
    <p class="infotext">
	<?php echo sprintf($this->getPhrase('Home/NewPassword', $sprache), $zugangscode);?>
    </p>
    <div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
	<?php echo form_label("E-Mail Adresse", "email", array("name" => "email", "for" => "email", "class" => "col-sm-3 control-label")) ?>
	<div class="col-sm-4">
	    <?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength' => 128, "type" => "email", "value" => $email, "class" => "form-control")); ?>
	    <?php echo form_error("email"); ?>
	</div>
    </div>
    <div class="form-group <?php echo (form_error("password") != "") ? 'has-error' : '' ?>">
	<?php echo form_label("Passwort", "password", array("name" => "password", "for" => "password", "class" => "col-sm-3 control-label")) ?>
	<div class="col-sm-4">
	    <?php echo form_input(array('id' => 'password', 'name' => 'password', "type" => "password", "class" => "form-control")); ?>
	    <?php echo form_error("password"); ?>
	</div>
    </div>
    <div class="form-group">
	<div class="col-sm-4 col-sm-offset-3">
	    <?php echo form_button(array("content"=>"Abschicken", "name"=>"submit_btn", "class"=>"btn btn-primary", "type"=>"submit")); ?>
	</div>
    </div>
    <?php
echo form_close();
//wirtes message if email adress exists
echo (isset($message)) ? $message : "";
?>
</div>
