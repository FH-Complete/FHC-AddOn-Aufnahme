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
		if((isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]])) && ($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]]->dms_id !== null))
		{
			$unvollständig = false;
		}
		
		if((isset($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]])) && ($dokumente[$this->config->item('dokumentTypen')["letztGueltigesZeugnis"]]->dms_id !== null))
		{
			$unvollständig = false;
		}
		?>
		<?php
			if((isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->mimetype !== null))
			{
				switch($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->mimetype)
				{
					case "application/pdf":
						$logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						$logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						$logo = "docx.gif";
					default:
						if(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->titel, "docx") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->titel, "doc") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						else
						{
							$logo = false;
							break;
						}
				}
			}
			elseif((isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype !== null))
			{
				switch($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype)
				{
					case "application/pdf":
						$logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						$logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						$logo = "docx.gif";
					default:
						if(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->titel, "docx") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->titel, "doc") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						else
						{
							$logo = false;
							break;
						}
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
						if((isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]])) && ($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]]->dms_id !== null))
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->dokument->name;
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
