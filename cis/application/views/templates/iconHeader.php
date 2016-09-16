<?php
/**
 * ./cis/application/views/templates/iconHeader.php
 *
 * @package default
 */
?>

<div id="iconHeader">
	<img id="logo_head" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>" />
	<?php echo $this->template->widget("login_info"); ?>
</div>
