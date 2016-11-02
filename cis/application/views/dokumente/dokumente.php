<?php
/**
 * ./cis/application/views/dokumente/dokumente.php
 *
 * @package default
 */
?>
<div id="dokumente">
	<div class="row document-header">
		<div class="col-sm-1">
			&nbsp;
		</div>
		<div class="col-sm-5">
			<?php echo $this->lang->line("dokumente_name"); ?>
		</div>
		<div class="col-sm-2">
			<?php echo $this->lang->line("dokumente_status"); ?>
		</div>
		<div class="col-sm-1">
			<?php echo $this->lang->line("dokumente_aktion"); ?>
		</div>
		<div class="col-sm-3">
			<?php echo $this->lang->line("dokumente_benoetigt"); ?>
		</div>
	</div>
	<?php
	foreach ($docs as $dok)
	{
		if(isset($dok->pflicht))
		{
			$p = ($dok->pflicht == true) ? ' *' : '';
		}
		else
		{
			$p = "*";
		}
	?>
		<div class="row document-row">
			<?php
			if((isset($dokumente[$dok->dokument_kurzbz]->mimetype)) && ($dokumente[$dok->dokument_kurzbz]->mimetype !== null))
			{
				if(isset($dokumente[$dok->dokument_kurzbz]->dokument))
				{
					if(strpos($dokumente[$dok->dokument_kurzbz]->dokument->name, ".docx") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$dok->dokument_kurzbz]->dokument->name, ".doc") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$dok->dokument_kurzbz]->dokument->name, ".pdf") !== false)
					{
						$logo = "document-pdf.svg";
					}
					elseif(strpos($dokumente[$dok->dokument_kurzbz]->dokument->name, ".jpg") !== false)
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
			<div id="<?php echo $dok->dokument_kurzbz; ?>_logo" class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
					<img class="document_logo center-block" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-5">
				<?php echo $dok->bezeichnung_mehrsprachig[$this->session->sprache->index-1].$p;?>
				<?php
				if((isset($dok->dokumentbeschreibung_mehrsprachig[$this->session->sprache->index-1])) && ($dok->dokumentbeschreibung_mehrsprachig[$this->session->sprache->index-1]!='null'))
				{
				?>
					<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $dok->dokumentbeschreibung_mehrsprachig[$this->session->sprache->index-1];?>"></span>
				<?php
					}
				?>
				<div id="<?php echo $dok->dokument_kurzbz; ?>_name"></div>
				<?php 
				if(isset($logo) && ($logo != false))
				{
					echo '<div>'.$dokumente[$dok->dokument_kurzbz]->dokument->name.'</div>';
				}
				else
				{
				?>
				<!-- The global progress bar -->
				<div id="<?php echo $dok->dokument_kurzbz; ?>Progress" class="progress" style="display:none;">
					<div class="progress-bar progress-bar-success"></div>
				</div>
				<?php
				}
				?>
			</div>
			<div class="col-sm-2">
				<?php
					if((isset($dok->dokument->nachgereicht)) && ($dok->dokument->nachgereicht == true))
					{
						echo $this->lang->line("dokumente_formNachgereicht");
					}
					elseif((isset($dokumente[$dok->dokument_kurzbz])) && ($dokumente[$dok->dokument_kurzbz]->accepted === true))
					{
						echo $this->lang->line("dokumente_formAkzeptiert");
					}
				?>
			</div>
			<div class="col-sm-1">
				<div id="<?php echo $dok->dokument_kurzbz;?>_delete">
				<?php
				if((isset($dok->dokument)) && ($dok->dokument->dms_id != null) && ($dokumente[$dok->dokument_kurzbz]->accepted == false))
				{
				?>
					<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dok->dokument->dms_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>
				<?php
				}
				?>
				</div>
				<div id="<?php echo $dok->dokument_kurzbz; ?>_Upload" class="upload-widget" style="<?php echo ((!isset($dok->dokument)) || ($dok->dokument->dms_id == null)) ? "" : "display: none;"; ?>">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<!-- The file input field used as target for the file upload widget -->
						<input id="<?php echo $dok->dokument_kurzbz; ?>FileUpload" type="file" name="files[]">
					</span>
				</div>
			</div>
			<div class="col-sm-3">
				<?php
				if(isset($dok->studiengaenge))
				{
					foreach($dok->studiengaenge as $stg)
					{
						if(isset($stg->studienplan))
						{
							echo $stg->bezeichnung." (".$stg->studienplan->orgform_kurzbz.")</br>";
						}
						else
						{
							echo $stg->bezeichnung."</br>";
						}
					}
				}
				
				?>
			</div>
		</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$(".fhc-tooltip").tooltip();
		
		// File upload
		$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Dokumente/uploadFiles/".$dok->dokument_kurzbz); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
				var uploadErrors = [];
				var acceptFileTypes = /^.*\.(jpe?g|docx?|pdf)$/i;

				if (typeof data.originalFiles[0]['size'] != 'undefined' && data.originalFiles[0]['size'] > 1024 * 1024 * 4)
				{
					uploadErrors.push('Datei zu groß');
				}
				if (typeof data.originalFiles[0]['name'] != 'undefined' && !acceptFileTypes.test(data.originalFiles[0]['name']))
				{
					uploadErrors.push('Kein zulässiger Dateityp');
				}
				if (uploadErrors.length > 0)
				{
					alert(uploadErrors.join("\n"));
				}
				else
				{
					data.originalFiles['<?php echo $dok->dokument_kurzbz; ?>'] = data.originalFiles[0];
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress').show();
					data.submit();
				}
			},
			done: function (e, data) {

				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $dok->dokument_kurzbz; ?>_delete').append('<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+');"><span class="glyphicon glyphicon-trash"></span></button>');
					$('#<?php echo $dok->dokument_kurzbz; ?>_Upload').hide();
					

					$('#<?php echo $dok->dokument_kurzbz; ?>Progress').hide();
					$('#<?php echo $dok->dokument_kurzbz; ?>_logo').show();
					var logo = "";
					if(data.result.bezeichnung.indexOf(".docx") !== false)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".doc") !== false)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".pdf") !== false)
					{
						logo = "document-pdf.svg";
					}
					else if(data.result.bezeichnung.indexOf(".jpg") !== false)
					{
						logo = "document-picture.svg";
					}
					else
					{
						logo = false;
					}

					$("#<?php echo $dok->dokument_kurzbz; ?>_logo").append('<img class="document_logo center-block" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					$("#<?php echo $dok->dokument_kurzbz; ?>_name").append(data.result.bezeichnung);

					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $dok->dokument_kurzbz; ?>_hochgeladen').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $dok->dokument_kurzbz; ?>Progress .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});
</script>
	<?php
	}
	?>
</div>
<script type="text/javascript">
	function deleteDocument(dms_id)
	{	
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/deleteDocument"); ?>',
			type: 'POST',
			data: {
				"dms_id": dms_id
			},
			cache: false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				if(data.error !== 0)
				{
					//TODO display error
				}
				else
				{
					$('#'+data.dokument_kurzbz+'_Upload').show();
					$('#'+data.dokument_kurzbz+'_delete').html("");
					$('#'+data.dokument_kurzbz+'_name').html('');
					$('#'+data.dokument_kurzbz+'Progress').hide();
					$('#'+data.dokument_kurzbz+'_logo').html('');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
	}
</script>
