<?php
/**
 * ./cis/application/views/summary/summary_requirements.php
 *
 * @package default
 */


?>
<legend><?php echo $this->lang->line("summary_requirements_header"); ?></legend>
<hr>
<div class="row">
    <div class="col-sm-12">
		<div class="col-sm-4">
			<?php echo $this->lang->line("summary_Abschlusszeugnis"); ?>
		</div>
		<?php
		$unvollständig = true;
		if((isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis_".$studiengang->typ]])) && ($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis_".$studiengang->typ]]->dms_id !== null))
		{
			$unvollständig = false;
		}
		
		if((isset($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]])) && ($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]]->dms_id !== null))
		{
			$unvollständig = false;
		}
		?>
		<?php
			if((isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->mimetype !== null))
			{
				if(isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument))
				{
					if(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument->name, ".docx") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument->name, ".doc") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument->name, ".pdf") !== false)
					{
						$logo = "document-pdf.svg";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument->name, ".jpg") !== false)
					{
						$logo = "document-picture.svg";
					}
					else
					{
						$logo = false;
					}
				}
				else
				{
					$logo = false;
				}
			}
			elseif((isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype !== null))
			{
				if(isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument))
				{
					if(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument->name, ".docx") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument->name, ".doc") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument->name, ".pdf") !== false)
					{
						$logo = "document-pdf.svg";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument->name, ".jpg") !== false)
					{
						$logo = "document-picture.svg";
					}
					else
					{
						$logo = false;
					}
				}
				else
				{
					$logo = false;
				}
			}
			else
			{
				$logo = "";
			}
			?>
			<div class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
		<div class="col-sm-6 <?php echo ($unvollständig) ? "incomplete" : ""; ?>">
			<div class="form-group">
			<?php if ($unvollständig)
					{
						echo $this->lang->line('summary_unvollstaendig');
					}
					else
					{
						if((isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis_".$studiengang->typ]])) && ($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis_".$studiengang->typ]]->dms_id !== null))
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dokument->name;
						}
						elseif((isset($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]])) && ($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]]->dms_id !== null))
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dokument->name;
						}
					}
					?>
			</div>
		</div>
    </div>
</div>
<hr>
