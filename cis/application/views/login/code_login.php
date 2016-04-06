<div class="panel panel-info">
    <div class="panel-heading text-center">
	<h3 class="panel-title"><?php echo $this->lang->line('login_AlreadyHaveAnAccessCode'); ?></h3>
    </div>
    <div class="panel-body text-center">
	<p>Dann loggen Sie sich hier ein</p>
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
	    if (isset($wrong_code) && $wrong_code)
		echo $this->lang->line('login_WrongCode');
	    ?>
	    <br>
	</div>
	<div class="form-group">
	    <div class="col-sm-4 col-sm-offset-4">
		<button class="btn btn-primary" type="submit" name="submit_btn">Login</button>
	    </div>
	    <div class="col-sm-4 col-sm-offset-4">
		<a href="<?php echo base_url("index.dist.php/Registration/resendCode") ?>">Zugangscode vergessen?</a>
	    </div>
	</div>
    </div>
</div>

