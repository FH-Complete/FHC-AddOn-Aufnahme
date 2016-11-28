<?php
/**
 * ./cis/application/views/language.php
 *
 * @package default
 */
?>

<div class="language pull-right">
	<?php 
		$studiengang_kz = "";
		if(isset($studiengang))
		{
			$studiengang_kz = $studiengang->studiengang_kz;
		}
	?>
	<span style="<?php echo ($sprache === "german") ? 'text-decoration: underline' : ''; ?>">
		<a href="<?php echo base_url($this->config->config["index_page"]."/".uri_string())."/?language=german&studiengang_kz=".$studiengang_kz;?>"><?php echo $this->lang->line('german'); ?></a>
	</span>
	<span>
		 | 
	</span>
	<span style="<?php echo ($sprache === "english") ? 'text-decoration: underline' : ''; ?>">
		<a href="<?php echo base_url($this->config->config["index_page"]."/".uri_string())."/?language=english&studiengang_kz=".$studiengang_kz;?>"><?php echo $this->lang->line('english'); ?></a>
	</span>
</div>
