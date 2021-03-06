<?php
/**
 * ./cis/application/views/login/uid_login.php
 *
 * @package default
 */


?>

<div class="panel panel-info">
    <div class="panel-heading text-center">
	<h3 class="panel-title">Studieren oder arbeiten Sie bereits an der FH Technikum Wien?</h3>
    </div>
    <div class="panel-body text-center">
	<p class="text-center">Dann loggen Sie sich hier mit Ihrem CIS-Account ein</p>
	<div class="form-group">
	    <label for="username" class="col-sm-3 control-label">Username</label>
	    <div class="col-sm-8">
		<input class="form-control" type="text" placeholder="Username" name="username">
	    </div>
	</div>
	<div class="form-group">
	    <label for="password" class="col-sm-3 control-label">Passwort</label>
	    <div class="col-sm-8">
		<input class="form-control" type="password" placeholder="Passwort" name="password">
	    </div>
	</div>
	<div class="form-group">
	    <span class="col-sm-4 col-sm-offset-4">
		<button class="btn btn-primary btn-lg icon-absenden" type="submit" name="submit_btn"><?php echo $this->lang->line('login_LoginButton'); ?></button>
	    </span>
	</div>
	<?php
if (isset($uid_error_msg))
	echo '<div class="alert alert-danger" role="alert">'.$uid_error_msg.'</div>';
?>
    </div>
</div>
