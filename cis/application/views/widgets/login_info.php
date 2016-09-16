<?php
/**
 * ./cis/application/views/widgets/login_info.php
 *
 * @package default
 */
?>

<div class="pull-right">
    <?php
		if (isset($name))
		{
	?>
			<span>eingeloggt als</span>
			<?php echo $name; ?>
			<a href="<?php echo base_url($this->config->config["index_page"]."/Logout/"); ?>">
				<button class="btn btn-default">Logout</button>
			</a>
    <?php
		}
    ?>
</div>
