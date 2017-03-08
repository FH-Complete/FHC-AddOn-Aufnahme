<?php
/**
 * ./cis/application/views/templates/iconHeader.php
 *
 * @package default
 */
?>

<div id="iconHeader">
	<div id="iconHeaderIcon">
		<img id="logo_head" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>" alt="header logo"/>
	</div>
	<div id="iconHeaderTitle">
	<?php
	if(isset($header))
	{
		echo "<h5>".$header."</h5>";
	}
	?>
	</div>
	<?php echo $this->template->widget("login_info"); ?>
</div>
