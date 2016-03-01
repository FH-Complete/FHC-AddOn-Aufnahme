<div class="panel panel-info">
	<div class="panel-heading text-center">
		<h3 class="panel-title"><?php echo $this->lang->line('login_AlreadyHaveAnAccessCode');?></h3>
	</div>
	<div class="panel-body text-center">
		<p>Dann loggen Sie sich hier ein</p>
		<div class="form-group">
			<div class="input-group col-sm-6 col-sm-offset-3">
				<p class="text-center"><input class="form-control" type="text" placeholder="Accesscode/Passphrase" name="code" autofocus="autofocus" value="<?php echo $code; ?>"></p>
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit" name="submit_btn">Login</button>
				</span>
			</div>
			<?php 
				if (isset($wrong_code) && $wrong_code)
					echo $this->lang->line('login_WrongCode');
			?>
			<br>
			<div class="col-sm-4 col-sm-offset-4">
				<a href="registration.php?method=resendcode">Zugangscode vergessen?</a>
			</div>
		</div>
	</div>
</div>
				
