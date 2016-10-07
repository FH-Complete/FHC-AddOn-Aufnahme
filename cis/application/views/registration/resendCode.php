<?php
/**
 * ./cis/application/views/registration.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->load->view('templates/metaHeader');
$this->lang->load(array('aufnahme', 'login'), $sprache);
?>

<div class="container">
    <?php $this->load->view('templates/iconHeader'); ?>
    <div class="row">
		<div id="resendCode">
<!--			<ol class="breadcrumb">
				<li class="active"><a href="<?php echo base_url($this->config->config["index_page"]."/Registration") ?>">Registration</a></li>
			</ol>-->
			<?php echo form_open("Registration/resendCode", array("id" => "ResendCodeForm", "name" => "ResendCodeForm", "class" => "form-horizontal")); ?>
			<!--<img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('/themes/'. $this->config->item('theme').'/images/logo.png'); ?>">-->

			<div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
			<?php echo form_label("E-Mail Adresse", "email", array("name" => "email", "for" => "email", "class" => "col-sm-3 control-label")) ?>
			<div class="col-sm-4">
				<?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength' => 128, "type" => "email", "class" => "form-control", "value" => set_value("email", isset($email) ? $email : "" ))); ?>
				<?php echo form_error("email"); ?>
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
				if (isset($error) && ($error->error === true))
					echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
			?>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footer');