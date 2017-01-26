<?php
/**
 * ./cis/application/views/summary/summary_requirements_specific.php
 *
 * @package default
 */


?>
<?php if((isset($dokumenteStudiengang[$studiengang->studiengang_kz])) && (!empty($dokumenteStudiengang[$studiengang->studiengang_kz]))) { ?>
	<legend><?php echo $this->lang->line("summary_specific_requirements_header"); ?></legend>
	<?php foreach ($dokumenteStudiengang[$studiengang->studiengang_kz] as $dok) { ?>
	<hr>
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-4">
				<?php echo $dok->bezeichnung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1];?>
			</div>
			<?php
				if((isset($dokumente[$dok->dokument_kurzbz]->mimetype)) && ($dokumente[$dok->dokument_kurzbz]->mimetype !== null))
				{
					if(isset($dokumente[$dok->dokument_kurzbz]->name))
					{
						if(strpos(strtolower($dokumente[$dok->dokument_kurzbz]->name), ".docx") !== false)
						{
							$logo = "docx.gif";
						}
						elseif(strpos(strtolower($dokumente[$dok->dokument_kurzbz]->name), ".doc") !== false)
						{
							$logo = "docx.gif";
						}
						elseif(strpos(strtolower($dokumente[$dok->dokument_kurzbz]->name), ".pdf") !== false)
						{
							$logo = "document-pdf.svg";
						}
						elseif(strpos(strtolower($dokumente[$dok->dokument_kurzbz]->name), ".jpg") !== false)
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
			<div class="col-sm-6<?php echo (!isset($dokumente[$dok->dokument_kurzbz])) ? " incomplete" : ""; ?>">
				<div class="form-group">
				<?php if (!isset($dokumente[$dok->dokument_kurzbz]))
						{
							echo $this->lang->line('summary_unvollstaendig');
						}
						elseif($dokumente[$dok->dokument_kurzbz]->nachgereicht == true)
						{
							echo $this->lang->line('summary_nachgereicht');
						}
						elseif(isset($dokumente[$dok->dokument_kurzbz]->name))
						{
							echo $dokumente[$dok->dokument_kurzbz]->name;
						}
				?>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } ?>
