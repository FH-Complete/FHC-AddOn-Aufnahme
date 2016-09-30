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
		<div class="col-sm-4">
			<?php echo $this->lang->line("summary_".$dok->dokument_kurzbz); ?>
		</div>
		<?php
			if((isset($dokumente[$dok->dokument_kurzbz]->mimetype)) && ($dokumente[$dok->dokument_kurzbz]->mimetype !== null))
			{
				switch($dokumente[$dok->dokument_kurzbz]->mimetype)
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
						if(strpos($dokumente[$dok->dokument_kurzbz]->titel, "docx") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						elseif(strpos($dokumente[$dok->dokument_kurzbz]->titel, "doc") !== false)
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
		<div class="col-sm-6<?php echo (!isset($dokumente[$dok->dokument_kurzbz])) ? " incomplete" : ""; ?>">
			<div class="form-group">
			<?php if (!isset($dokumente[$dok->dokument_kurzbz]))
					{
						echo $this->lang->line('summary_unvollstaendig');
					}
					elseif($dokumente[$dok->dokument_kurzbz]->nachgereicht == "t")
					{
						echo $this->lang->line('summary_nachgereicht');
					}
					else
					{
						echo $dokumente[$dok->dokument_kurzbz]->dokument->name;
					}
			?>
			</div>
		</div>
    </div>
</div>
<hr>
<?php } ?>
