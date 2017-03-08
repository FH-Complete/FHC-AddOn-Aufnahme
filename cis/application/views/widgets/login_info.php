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
            <div style="float: left;">
                <span>eingeloggt als</span>
                <?php echo $name; ?>
            </div>
            <div style="float: left; margin-left: 10px;">
                <form action="<?php echo base_url($this->config->config["index_page"]."/Logout/"); ?>">
                    <input class="btn btn-default" type="submit" value="Logout" />
                </form>
            </div>
    <?php
		}
    ?>
</div>
