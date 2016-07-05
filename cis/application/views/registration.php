<?php $this->lang->load(array('aufnahme'), $sprache);?>

<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Registration</li>
    </ol>
    
    <?php echo form_open("Registration?studiengang_kz=".$studiengang_kz,array("id"=>"RegistrationLoginForm", "name"=>"RegistrationLoginForm", "class"=>"form-horizontal")); ?>
	<img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('/themes/'. $this->config->item('theme').'/images/logo.png'); ?>">	
	<h2 class="text-center"><?php echo $this->lang->line('aufnahme/login_greeting_text'); ?></h2>		
	<p class="infotext">
		<?php echo $this->getPhrase("Registration/RegistrationForm", $sprache); ?>
	</p>

	<div class="form-group <?php echo (form_error("vorname")!="")? 'has-error': '' ?>">
	    <?php echo form_label($this->lang->line('aufnahme/vorname'), "vorname", array("name"=>"vorname","for"=>"vorname", "class"=>"col-sm-3 control-label")) ?>
	    <div class="col-sm-4">
		<?php echo form_input(array('id' => 'vorname', 'name' => 'vorname', 'maxlength'=>32, "type"=>"text", "value"=>set_value("vorname"), "class"=>"form-control")); ?>
		<?php echo form_error("vorname");?>
	    </div>
	</div>
	<div class="form-group <?php echo (form_error("nachname")!="")? 'has-error': '' ?>">
	    <?php echo form_label($this->lang->line('aufnahme/nachname'), "nachname", array("name"=>"nachname","for"=>"nachname", "class"=>"col-sm-3 control-label")) ?>
	    <div class="col-sm-4">
		<?php echo form_input(array('id' => 'nachname', 'name' => 'nachname', 'maxlength'=>64, "type"=>"text", "value"=>set_value("nachname"), "class"=>"form-control")); ?>
		<?php echo form_error("nachname");?>
	    </div>
	</div>
	<div class="form-group <?php echo (form_error("geb_datum")!="")? 'has-error': '' ?>">
	    <?php echo form_label($this->lang->line('aufnahme/geburtsdatum'), "geb_datum", array("name"=>"geb_datum","for"=>"geb_datum", "class"=>"col-sm-3 control-label")) ?>
	    <div class="col-sm-4">
		<?php echo form_input(array('id' => 'vorname', 'name' => 'geb_datum', 'placeholder'=>'tt.mm.jjjj', "type"=>"datetime", "value"=>set_value("geb_datum"), "class"=>"form-control")); ?>
		<?php echo form_error("geb_datum");?>
	    </div>
	</div>
	<div class="form-group <?php echo (form_error("email")!="")? 'has-error': '' ?>">
	    <?php echo form_label($this->lang->line('aufnahme/emailAdresse'), "email", array("name"=>"email","for"=>"email", "class"=>"col-sm-3 control-label")) ?>
	    <div class="col-sm-4">
		<?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength'=>128, "type"=>"email", "value"=>set_value("email"), "class"=>"form-control")); ?>
		<?php echo form_error("email");?>
	    </div>
	</div>
	<div class="form-group <?php echo (form_error("email2")!="")? 'has-error': '' ?>">
	    <?php echo form_label($this->lang->line('aufnahme/wiederholungEmail'), "email2", array("name"=>"email2","for"=>"email2", "class"=>"col-sm-3 control-label")) ?>
	    <div class="col-sm-4">
		<?php echo form_input(array('id' => 'email2', 'name' => 'email2', 'maxlength'=>128, "type"=>"email", "value"=>set_value("email2"), "class"=>"form-control")); ?>
		<?php echo form_error("email2");?>
	    </div>
	</div>
	<div class="form-group">
	    <div class="col-sm-3">
		<img id="captcha" src="<?=site_url('/Registration/securimage')?>" alt='captcha' class="center-block img-responsive" />
                <!-- TODO set link -->
		<a href="#" onclick="document.getElementById('captcha').src = 'https://cis.technikum-wien.at/include/securimage/securimage_show.php?' + Math.random();return false">
		    Andere Grafik
		</a>
	    </div>
	    <div class="col-sm-4 <?php echo (form_error("captcha_code")!="")? 'has-error': '' ?>">
		<?php echo $this->getPhrase("Registration/SpamProtection", $sprache); ?>
		<?php echo form_input(array('id' => 'captcha', 'name' => 'captcha_code', 'maxlength'=>6, "type"=>"text", "class"=>"form-control")); ?>
		<?php echo form_input(array('id' => 'zugangscode', 'name' => 'zugangscode', "type"=>"hidden", "value"=>set_value(uniqid()))); ?>
		<?php echo form_error("captcha_code");?>
	    </div>
	</div>
	<div class="form-group">
	    <div class="col-sm-4 col-sm-offset-3">
		<?php echo form_submit(array("value"=>"Abschicken", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
	    </div>
	</div>
    <?php echo form_close(); 
    //wirtes message if email adress exists
	echo (isset($message))? $message:"";
    ?>
	
</div>

<script type="text/javascript">

	function changeSprache(sprache)
	{
		var method = 'registration';

		window.location.href = "registration.php?sprache=" + sprache + "&method=" + method + "&stg_kz=";
	}

	function validateEmail(email)
	{
		//var email = document.ResendCodeForm.email.value;
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		if (re.test(email) === false)
		{
			alert("Bitte geben Sie eine gültige E-Mail-Adresse ein.");
			return false;
		}
		else
			return true;
	}

</script>


