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
		if ($stg->typ == "b") {

?>
	    <h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3>
	    <div id="<?php echo $stg->studiengang_kz; ?>">
		<div class="row">
		    <div class="col-sm-12">
			<?php echo $this->getPhrase("Termine/BewerbungIstEingelangt", $sprache, $this->config->item('root_oe')); ?>
		    </div>
		</div>
		<h4><?php echo $this->lang->line("termine/erstesAufnahmeverfahren"); ?></h4>
		<div class="row">
		    <?php echo form_open("/Aufnahmetermine/register/".$stg->studiengang_kz."/".$stg->studienplan->studienplan_id, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
		    <div class="col-sm-4">
			<?php
			if (!empty($reihungstests[$stg->studiengang_kz][1])) {
?>
			<div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
			    <?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][1], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
			    <?php echo form_error("rtTermin"); ?>
			</div>
			<?php
			}
			else {
				echo $this->lang->line("termine/keineTermineVorhanden");
			}
?>
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
			if (!empty($reihungstests[$stg->studiengang_kz][2])) {
?>
		<h4><?php echo $this->lang->line("termine/zweitesAufnahmeverfahren"); ?></h4>
		<div class="row">
		    <?php echo form_open("/Aufnahmetermine/register/".$stg->studiengang_kz."/".$stg->studienplan->studienplan_id, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
		    <div class="col-sm-4">
			<?php
				if (!empty($reihungstests[$stg->studiengang_kz][2]))
				{
?>
			<div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
			    <?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][2], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
			    <?php echo form_error("rtTermin"); ?>
			</div>
			<?php
				}
				else
				{
					echo $this->lang->line("termine/keineTermineVorhanden");
				}
?>
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
?>
	    </div>
	    <?php
		}
	}
}
