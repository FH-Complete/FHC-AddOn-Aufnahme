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
		<div id="confirm">
			<ol class="breadcrumb">
			<li class="active"><a href="<?php echo base_url($this->config->config["index_page"]."/Registration") ?>">Registration</a></li>
			</ol>
			<?php echo form_open("Registration/code_login?studiengang_kz=".((isset($studiengang_kz)) ? $studiengang_kz : ""), array("id" => "ChangePasswordForm", "name" => "ChangePasswordForm", "class" => "form-horizontal")); ?>
			<div class="row">
				<div class="col-sm-12">
					<img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<p class="infotext">
						<?php echo sprintf($this->getPhrase('Home/NewPassword', $sprache), $zugangscode);?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
						<?php echo form_label("E-Mail Adresse", "email", array("name" => "email", "for" => "email", "class" => "col-sm-3 control-label")) ?>
						<div class="col-sm-4">
							<?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength' => 128, "type" => "email", "value" => $email, "class" => "form-control")); ?>
							<?php echo form_error("email"); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group <?php echo (form_error("password") != "") ? 'has-error' : '' ?>">
						<?php echo form_label("Passwort", "password", array("name" => "password", "for" => "password", "class" => "col-sm-3 control-label")) ?>
						<div class="col-sm-4">
							<?php echo form_input(array('id' => 'password', 'name' => 'password', "type" => "password", "class" => "form-control")); ?>
							<?php echo form_error("password"); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-3">
							<?php echo form_button(array("content"=>"Abschicken", "name"=>"submit_btn", "class"=>"btn btn-primary", "type"=>"submit")); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php
						echo form_close();
						//wirtes message if email adress exists
						echo (isset($message)) ? $message : "";
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footer');
