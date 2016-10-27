<?php
/**
 * ./cis/application/views/login/code_login.php
 *
 * @package default
 */


?>
<div class="panel panel-info">
    <div class="panel-heading text-center">
	<h3 class="panel-title"><?php echo $this->getPhrase("Home/AccessCodeAvailable", $sprache, $this->config->item('root_oe')); ?></h3>
    </div>
    <div class="panel-body text-center">
	<p><?php echo $this->getPhrase("Home/Login", $sprache, $this->config->item('root_oe')); ?></p>
	<div class="form-group">
	    <div class="input-group col-sm-6 col-sm-offset-3">
		<p class="text-center"><input class="form-control" type="text" placeholder="E-Mail" name="email" autofocus="autofocus" value=""></p>
	    </div>
	</div>
	<div class="form-group">
	    <div class="input-group col-sm-6 col-sm-offset-3">
		<p class="text-center"><input class="form-control" type="text" placeholder="Accesscode/Passphrase" name="code" autofocus="autofocus" value="<?php echo $code; ?>"></p>
	    </div>
	    <?php
if (isset($code_error_msg))
	echo '<div class="alert alert-danger" role="alert">'.$code_error_msg.'</div>';
?>
	    <br>
	</div>
	<div class="form-group">
	    <div class="col-sm-4 col-sm-offset-4">
		<button class="btn btn-primary icon-absenden" type="submit" name="submit_btn"><?php echo $this->lang->line('login_LoginButton'); ?></button>
	    </div>
	    <div class="col-sm-4 col-sm-offset-4">
		<a href="<?php echo base_url($this->config->config["index_page"]."/Registration/resendCode") ?>"><?php echo $this->lang->line('aufnahme/zuganscodeVergessen');?></a>
	    </div>
	</div>
    </div>
</div>
