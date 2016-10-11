<?php
/**
 * ./cis/application/views/registration/registration.php
 *
 * @package default
 */


?>
<div id="registration">
    <?php echo form_open("Registration?studiengang_kz=".$studiengang_kz, array("id"=>"RegistrationLoginForm", "name"=>"RegistrationLoginForm", "class"=>"form-horizontal")); ?>
    <!--<img style="width:150px;" class="center-block img-responsive" src="<?php echo base_url('/themes/'. $this->config->item('theme').'/images/logo.png'); ?>">	-->
    <h1 class="text-center"><?php echo $this->getPhrase("Registration/RegistrationGreetingText", $sprache); ?></h1>
    <p class="infotext">
	    <?php echo $this->getPhrase("Registration/RegistrationForm", $sprache); ?>
    </p>
    <div class="row">
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("vorname")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/vorname'), "vorname", array("name"=>"vorname", "for"=>"vorname", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'vorname', 'name' => 'vorname', 'maxlength'=>32, "type"=>"text", "value"=>(isset($success) && $success == true) ? "" : set_value("vorname"), "class"=>"form-control")); ?>
		    <?php echo form_error("vorname");?>
		</div>
	    </div>
	</div>
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("nachname")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/nachname'), "nachname", array("name"=>"nachname", "for"=>"nachname", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'nachname', 'name' => 'nachname', 'maxlength'=>64, "type"=>"text", "value"=>(isset($success) && $success == true) ? "" : set_value("nachname"), "class"=>"form-control")); ?>
		    <?php echo form_error("nachname");?>
		</div>
	    </div>
	</div>
    </div>
    <div class="row">
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("geb_datum")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/geburtsdatum'), "geb_datum", array("name"=>"geb_datum", "for"=>"geb_datum", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'geb_datum', 'name' => 'geb_datum', 'placeholder'=>'', "type"=>"text", "value"=>(isset($success) && $success == true) ? "" : set_value("geb_datum"), "class"=>"form-control datepicker")); ?>
		    <?php echo form_error("geb_datum");?>
		</div>
	    </div>
	</div>
	<!--<div class="col-lg-6 col-sm-6">
            <div class="form-group <?php echo (form_error("geschlecht") != "") ? 'has-error' : '' ?>">
                <label class="col-sm-11 control-label"><?php echo $this->lang->line('aufnahme/Geschlecht'); ?></label>
		<div class="col-sm-11">
		    <?php echo form_radio(array("id" => "geschlecht_m", "name" => "geschlecht"), "m" , (isset($person->geschlecht) && $person->geschlecht=="m") ? true : false); ?>
		    <span><?php echo $this->lang->line("aufnahme/Maennlich"); ?></span>
		    <?php echo form_radio(array("id" => "geschlecht_f", "name" => "geschlecht"), "f", (isset($person->geschlecht) && $person->geschlecht=="f") ? true : false); ?>
		    <span><?php echo $this->lang->line("aufnahme/Weiblich"); ?></span>
		    <?php echo form_error("geschlecht"); ?>
		</div>
            </div>
        </div>
    </div>
    <div class="row">
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("wohnort")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/wohnort'), "wohnort", array("name"=>"wohnort","for"=>"wohnort", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'wohnort', 'name' => 'wohnort', 'placeholder'=>'', "type"=>"text", "value"=>(isset($success) && $success == true) ? "" : set_value("wohnort"), "class"=>"form-control")); ?>
		    <?php echo form_error("wohnort");?>
		</div>
	    </div>
	</div>-->
    </div>
    <div class="row">
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("email")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/emailAdresse'), "email", array("name"=>"email","for"=>"email", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'email', 'name' => 'email', 'maxlength'=>128, "type"=>"email", "value"=>(isset($success) && $success == true) ? "" : set_value("email"), "class"=>"form-control")); ?>
		    <?php echo form_error("email");?>
		</div>
	    </div>
	</div>
	<div class="col-lg-6 col-sm-6">
	    <div class="form-group <?php echo (form_error("email2")!="")? 'has-error': '' ?>">
		<?php echo form_label($this->lang->line('aufnahme/wiederholungEmail'), "email2", array("name"=>"email2","for"=>"email2", "class"=>"col-sm-11 control-label")) ?>
		<div class="col-sm-11">
		    <?php echo form_input(array('id' => 'email2', 'name' => 'email2', 'maxlength'=>128, "type"=>"email", "value"=>(isset($success) && $success == true) ? "" : set_value("email2"), "class"=>"form-control")); ?>
		    <?php echo form_error("email2");?>
		</div>
	    </div>
	</div>
    </div>
    <div class="row">
	<div class="col-lg-11 col-sm-11">
	    <div class="form-group <?php echo (form_error("datenschutz") != "") ? 'has-error' : '' ?>">
		<div class="col-lg-11 col-sm-11">
		    <div class="checkbox">
			<label>
			    <?php echo form_checkbox(array('id' => 'datenschutz', 'name' => 'datenschutz', "checked" => isset($this->input->post()["datenschutz"]) ? TRUE : FALSE, "class"=>"datenschutz"));
					echo $this->getPhrase("Registration/Datenschutz", $sprache);
				?>
			</label>
			<!--<a href="<?php echo $this->config->item('LinkDatenschutz') ? $this->config->item('LinkDatenschutz') : ''; ?>" target="_blank">Link</a>-->
		    </div>
		    <?php echo form_error("datenschutz"); ?>
		</div>
	    </div>
	</div>
    </div>
    <div class="row">
		<div class="col-lg-11 col-sm-11">
			<div class="form-group">
				<div class="col-sm-3">
					<img id="captcha" src="<?php echo site_url('/Registration/securimage') ?>" alt='captcha' class="center-block img-responsive" />
					<!-- TODO set link -->
					<a onclick="document.getElementById('captcha').src = '<?php echo base_url($this->config->config["index_page"] . '/Registration/securimage'); ?>/'+Math.random();">
						Andere Grafik
					</a>
				</div>
			</div>
		</div>
    </div>
	<div class="row">
		<div class="col-sm-8 <?php echo (form_error("captcha_code") != "") ? 'has-error' : '' ?>">
			<div class="form-group">
				<div class="col-sm-12">
					<?php echo $this->getPhrase("Registration/SpamProtection", $sprache); ?>
					<?php echo form_input(array('id' => 'captcha', 'name' => 'captcha_code', 'maxlength' => 6, "type" => "text", "class" => "form-control")); ?>
					<?php echo form_input(array('id' => 'zugangscode', 'name' => 'zugangscode', "type" => "hidden", "value" => set_value(uniqid()))); ?>
					<?php echo form_error("captcha_code"); ?>
				</div>
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-lg-6 col-sm-6">
			<div class="form-group">
				<div class="col-lg-6 col-sm-6">
					<?php echo form_button(array("id"=>"registration_button", "content"=>$this->lang->line("aufnahme/registrieren"), "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
				</div>
			</div>
		</div>
    </div>
    <?php echo form_close();
//wirtes message if email adress exists
echo (isset($message))? $message:"";
if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>
</div>

<script type="text/javascript">
    $(document).ready(function(){

		$(".datepicker").datepicker({
			dateFormat: "dd.mm.yy",
			maxDate: new Date(),
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 10);
				}, 0);
			},
			changeYear: true,
			yearRange: '1900:c'
			<?php
			if(ucfirst($sprache) === "German")
			{
				?>

					,monthNames: ["Jänner", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
					dayNamesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
					dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"]
				<?php
			}
			?>
		});
		
		
    });
</script>
