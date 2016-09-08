<?php
/**
 * ./cis/application/views/studiengaenge/bachelor.php
 *
 * @package default
 */


?>
<h2 class="bachelor_header"><?php echo $this->lang->line('studiengaenge/bachelor'); ?></h2>

<?php
foreach ($studiengaenge as $stg) {
	if ($stg->typ == "b") {
		foreach ($stg->studienplaene as $studienplan) {
?>
		<a class="collapsed collapseLink" data-toggle='collapse' data-target='#<?php echo $studienplan->studienplan_id; ?>'><?php echo $stg->bezeichnung ?> (<?php echo $studienplan->orgform_kurzbz; ?>)</a></br>
		<div id="<?php echo $studienplan->studienplan_id; ?>" class='collapse collapsePanel'>
		<div class="stgContent">
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/abschluss') ?>: </div><div class="col-sm-6">Bachelor of Science in Engineering (BSc)</div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/dauer'); ?>: </div><div class="col-sm-6"><?php echo $studienplan->regelstudiendauer; ?> Semester</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
			<?php echo $this->lang->line('studiengaenge/orgform'); ?>:
			</div>
			<div class="col-sm-6">
			<?php foreach ($orgform as $of) {
				if ($of->orgform_kurzbz == $studienplan->orgform_kurzbz) {
					echo $of->bezeichnung;
				}
			} ?>
			</div>
		</div>
		<!--<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/studienplaetze'); ?>: </div><div class="col-sm-6"></div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/studeingebuehr'); ?>: </div><div class="col-sm-6"></div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/bewerbungsfrist'); ?>: </div>
			<?php $bewerbungMoeglich = false;
			if (!empty($studienplan->fristen))
			{
				foreach($studienplan->fristen as $frist)
				{
					if((date("Y-m-d", strtotime($frist->beginn)) < date("Y-m-d")) && (date("Y-m-d", strtotime($frist->ende)) > date("Y-m-d")))
					{
						$bewerbungMoeglich = true;
					}
				}
			}
?>
			<div class="col-sm-6 frist"<?php echo (!$bewerbungMoeglich) ? "studienplan_id='".$studienplan->studienplan_id."'" : ""; ?>>
			<?php if(!empty($studienplan->fristen))
			{
				$dateString = "";
				foreach($studienplan->fristen as $frist)
				{
					$dateString .= date("d.m.Y", strtotime($frist->beginn))." ".$this->lang->line("studiengaenge/bis")." ".date("d.m.Y", strtotime($frist->ende))."</br>";
				}

				echo $dateString;
			}
			else
			{
				echo $this->lang->line("studiengaenge/keineBewerbungMoeglich");
			}
?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/aufnahmetermine'); ?>: </div>
			<div class="col-sm-6">
			<?php if(!empty($stg->reihungstests))
			{
				$dateString = "";
				foreach($stg->reihungstests as $rt)
				{
					$dateString .= date("d.m.Y", strtotime($rt->datum)).", ";
				}

				echo substr($dateString, 0, -2);
			}
			else
			{
				echo $this->lang->line("studiengaenge/keineAufnahmetermineVorhanden");
			}
?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/studienbeginn'); ?>: </div><div class="col-sm-6"><?php echo $studiensemester->start; ?></div>
		</div>
		<div class="row">
			<div class="col-sm-3"><?php echo $this->lang->line('studiengaenge/weiterführend'); ?>: </div><div class="col-sm-6"></div>
		</div>-->

		<?php $bewerbungMoeglich = false;
			if(!empty($studienplan->fristen))
			{
				foreach($studienplan->fristen as $frist)
				{
					if((date("Y-m-d", strtotime($frist->beginn)) < date("Y-m-d")) && (date("Y-m-d", strtotime($frist->ende)) > date("Y-m-d")))
					{
						$bewerbungMoeglich = true;
						?>
						<div class="row">
						<div class="col-sm-3 col-md-offset-3"><a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/studiengang/".$stg->studiengang_kz."/".$studienplan->studienplan_id."/".$frist->studiensemester_kurzbz) ?>"><button id="button_<?php echo $studienplan->studienplan_id; ?>" type="button" class="btn btn-sm icon-bewerben"><?php echo $this->lang->line('studiengaenge/buttonText'); ?></button></a></div>
						</div>
						<?php
					}
				}
			}

			if($bewerbungMoeglich)
			{
				/*
?>
			<div class="row">
			<div class="col-sm-3 col-md-offset-3"><a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/studiengang/".$stg->studiengang_kz."/".$studienplan->studienplan_id) ?>"><button id="button_<?php echo $studienplan->studienplan_id; ?>" type="button" class="btn btn-sm icon-bewerben"><?php echo $this->lang->line('studiengaenge/buttonText'); ?></button></a></div>
			</div>
			<?php*/
			}
			else
			{
?>
			<div class="row">
			<div class="col-sm-3 col-md-offset-3"><a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/studiengang/".$stg->studiengang_kz."/".$studienplan->studienplan_id) ?>"><button id="button_<?php echo $studienplan->studienplan_id; ?>" type="button" class="btn btn-sm icon-bewerben" disabled title="<?php echo $this->lang->line("studiengaenge/BewerbungNichtMoeglich"); ?>"><?php echo $this->lang->line('studiengaenge/buttonText'); ?></button></a></div>
			</div>
		<?php
			}
?>
		</div>
		</div>
		<?php
		}
	}
}
?>
