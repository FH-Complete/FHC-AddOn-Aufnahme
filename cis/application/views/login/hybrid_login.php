<?php
/**
 * ./cis/application/views/login/hybrid_login.php
 *
 * @package default
 */


?>
<div id="login">
    <div class="panel panel-info">
	<div class="panel-heading">
	    <h3 class="panel-title"><?php echo $this->getPhrase("Home/AccessCodeAvailable", $sprache, $this->config->item('root_oe')); ?></h3>
	</div>
	<div class="panel-body">
	    <p><?php echo $this->getPhrase("Home/Login", $sprache, $this->config->item('root_oe')); ?></p>
	    <form id="LoginForm" action="<?php echo base_url($this->config->config["index_page"] . '/Login'); ?>" method="POST">
		<div class="form-group">
		    <label><?php echo $this->lang->line('aufnahme/loginEmailAdresse'); ?></label>
		    <div class="input-group col-sm-12">
			<input class="form-control" type="text" name="email" value="">
		    </div>
		</div>
		<div class="form-group">
		    <label><?php echo $this->lang->line('aufnahme/password'); ?></label>
		    <div class="input-group col-sm-12">
			<input class="form-control" type="password" name="code" value="">
		    </div>
		    <?php
if (isset($code_error_msg))
	echo '<div class="alert alert-danger" role="alert">' . $code_error_msg . '</div>';
?>
		    <br>
		</div>
		<div class="form-group">
		    <div class="pull-right">
			<button class="btn btn-primary icon-absenden" type="submit" name="submit_btn"><?php echo $this->lang->line('login_LoginButton'); ?></button>
		    </div>
		</div>
		<div class="form-group">
		    <div class="col-sm-12">
			<a href="<?php echo base_url($this->config->config["index_page"] . "/Registration/resendCode") ?>"><?php echo $this->lang->line('aufnahme/zugangscodeVergessen');?></a>
		    </div>
		</div>
	    </form>
	</div>
    </div>
</div>
