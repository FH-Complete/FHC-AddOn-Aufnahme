<?php
/**
 * ./cis/application/views/aufnahmetermine/bachelor_termine.php
 *
 * @package default
 */


if (isset($anmeldeMessage))
	echo '<div class="alert alert-danger" role="alert">'.$anmeldeMessage.'</div>';

if (!empty($studiengaenge)) {
	foreach ($studiengaenge as $stg) {
		if ($stg->typ == "m") {

?>
	    <h3>Master / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3>
	    <div id="<?php echo $stg->studiengang_kz; ?>">
		<div class="row">
		    <div class="col-sm-12">
			<?php echo $this->getPhrase("Termine/BewerbungIstEingelangt", $sprache, $this->config->item('root_oe')); ?>
		    </div>
		</div>
		<h4><?php echo $this->lang->line("termine/erstesAufnahmeverfahren"); ?></h4>
		<?php
			if (!empty($reihungstests[$stg->studiengang_kz][1]))
			{
				if(empty($rt_person[$stg->studiengang_kz]))
				{
				?>
				<div class="row">
					<?php echo form_open("/Aufnahmetermine/register/".$stg->studiengang_kz."/".$stg->studienplan->studienplan_id, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
					<div class="col-sm-4">

						<div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
							<?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][1], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
							<?php echo form_error("rtTermin"); ?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<?php echo form_button(array("content"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
						</div>
					</div>
					<?php
					echo form_close();
					?>
				</div>
				<?php
				}
				else
				{
					?>
					<div class="row">
						<div class="col-sm-8">
							<span class="selectedTerminHeader"><?php echo $this->lang->line("termine/gewaehlterTermin"); ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<span class="selectedTermin"><?php echo $reihungstests[$stg->studiengang_kz][1][$rt_person[$stg->studiengang_kz]]; ?></span>
						</div>
					</div>
					<?php
				}
			}
			else {
				echo $this->lang->line("termine/keineTermineVorhanden");
			}
		?>
		<?php
			if (!empty($reihungstests[$stg->studiengang_kz][2])) {
		?>
		
		<?php
			if (!empty($reihungstests[$stg->studiengang_kz][2]))
			{
				if(empty($rt_person[$stg->studiengang_kz]))
				{
				?>
				<div class="row">
					<?php echo form_open("/Aufnahmetermine/register/".$stg->studiengang_kz."/".$stg->studienplan->studienplan_id, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
					<div class="col-sm-4">

						<div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
							<?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][2], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
							<?php echo form_error("rtTermin"); ?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<?php echo form_button(array("content"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
						</div>
					</div>
					<?php
					echo form_close();
					?>
				</div>
				<?php
				}
				else
				{
					?>
					<div class="row">
						<div class="col-sm-8">
							<span class="selectedTerminHeader"><?php echo $this->lang->line("termine/gewaehlterTermin"); ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<span class="selectedTermin"><?php echo $reihungstests[$stg->studiengang_kz][1][$rt_person[$stg->studiengang_kz]]; ?></span>
						</div>
					</div>
					<?php
				}
			}
			else {
				echo $this->lang->line("termine/keineTermineVorhanden");
			}
		?>
		<?php
			}
?>
	    </div>
	    <?php
		}
	}
}
