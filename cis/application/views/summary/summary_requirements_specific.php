<?php
/**
 * ./cis/application/views/summary/summary_requirements_specific.php
 *
 * @package default
 */


?>
<legend><?php echo $this->lang->line("summary_specific_requirements_header"); ?></legend>
<?php foreach ($dokumenteStudiengang[$studiengang->studiengang_kz] as $dok) { ?>
<hr>
<div class="row">
    <div class="col-sm-12">
		<div class="col-sm-6">
			<?php echo $this->lang->line("summary_".$dok->dokument_kurzbz); ?>
		</div>
		<div class="col-sm-5<?php echo (!isset($dokumente[$dok->dokument_kurzbz])) ? " incomplete" : ""; ?>">
			<div class="form-group">
			<?php if (!isset($dokumente[$dok->dokument_kurzbz]))
					{
						echo $this->lang->line('summary_unvollstaendig');
					}
					else {
						echo $this->lang->line('summary_dokumentVorhanden');
					}
			?>
			</div>
		</div>
    </div>
</div>
<hr>
<?php } ?>
