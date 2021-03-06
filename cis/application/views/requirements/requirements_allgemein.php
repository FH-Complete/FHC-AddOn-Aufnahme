<?php
/**
 * ./cis/application/views/requirements/requirements_allgemein.php
 *
 * @package default
 */
?>

<legend><?php echo $this->lang->line("requirements_header"); ?></legend>
<?php
	echo form_open_multipart("Requirements/?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplaene[0]->studienplan_id, array("id" => "RequirementsForm", "name" => "RequirementsForm"));
?>
<input type="hidden" name="studiengang_kz" value="<?php echo $studiengang->studiengang_kz; ?>"/>
<input type="hidden" name="studienplan_id" value="<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>"/>
<div class="row">
    <div class="col-sm-12">
		<span><?php echo $this->getPhrase("ZGV/introduction_short", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></span>
		<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->getPhrase("ZGV/introduction_long", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"></span>
		<?php
			if (isset($error) && ($error->error === true) && ((!isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]])) || (isset($optionError))))
			{
				?>
					<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">Error:</span>
						<?php echo $this->lang->line("requirements_selectOption"); ?>
					  </div>
				<?php
			}

			$phrase = $this->config->item("ZgvOptionsMapping");
			if((is_array($phrase)) && (isset($phrase[$studiengang->typ])))
			{
				echo $this->getPhrase($phrase[$studiengang->typ], $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz);
			}
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$.ajax({
					url: '<?php echo base_url($this->config->config["index_page"]."/Helper/getOption"); ?>',
					type: 'POST',
                    data: {
					  studiengangtyp:  '<?php echo $studiengang->typ;?>'
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
							$("input[value='"+data.result+"']").prop("checked", true);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						// Handle errors here
						console.log('ERRORS: ' + textStatus);
						// STOP LOADING SPINNER
					}
				});
			});
		</script>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $this->getPhrase("ZGV/UploadDiploma", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>
    </div>
</div>
<div class="row form-upload">
	<?php
	if((isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->mimetype !== null))
	{
		if(isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name))
		{
			if(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name), ".docx") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name), ".doc") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name), ".pdf") !== false)
			{
				$logo = "document-pdf.svg";
			}
			elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name), ".jpg") !== false)
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
	<div id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="col-sm-1">
		<?php
		if(isset($logo) && ($logo != false))
		{
		?>
		<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
		<?php
		}
		?>
	</div>
    <div class="col-sm-6">
		<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_hochgeladen'; ?>">
			<?php
				if ((!isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]))
						|| ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->nachgereicht === true)
						|| ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id === null))
				{
					echo $this->lang->line('requirements_keinDokHochgeladen');
				}
				else
				{
					echo $dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->name."</br>";
					echo $this->lang->line('requirements_DokHochgeladen');
				}
			?>
			<!-- The global progress bar -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="progress" <?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]) && $dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id !== null) ? 'style="display: none"': '';?>>
					<div class="progress-bar progress-bar-success"></div>
				</div>
		</div>
		<div class="checkbox">
			<label>
				<?php
					$data = array('id' => $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_nachgereicht_'.$studiengang->studienplaene[0]->studienplan_id, 'name' => $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_nachgereicht_'.$studiengang->studienplaene[0]->studienplan_id, "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->nachgereicht === true)) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplaene[0]->studienplan_id, "class"=>"nachreichen_checkbox_zeugnis");
					(isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
					(isset($abgeschickt_array[$studiengang->studiengang_kz]) && ($abgeschickt_array[$studiengang->studiengang_kz] == true)) ? $data["disabled"] = "disabled" : false;
					echo form_checkbox($data);
					echo $this->lang->line('requirements_formNachgereicht')
				?>
			</label>
		</div>
		<div class="form-group">
            <div class="form-group <?php echo ((isset($geplanter_abschluss_date_fehlt))) ? 'has-error' : '' ?>">
				<div id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_nachreichenDatum_'.$studiengang->studienplaene[0]->studienplan_id.'_div'; ?>" class="">
					<?php echo form_label($this->lang->line('requirements_nachreichenAbschlussGeplantDatum'), "nachreichenDatum", array("name" => "nachreichenDatum", "for" => "nachreichenDatum", "class" => "control-label")) ?>
					<?php echo form_input(array('id' => $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_nachreichenDatum_'.$studiengang->studienplaene[0]->studienplan_id, 'name' => $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_nachreichenDatum_'.$studiengang->studienplaene[0]->studienplan_id, 'maxlength' => 64, "type" => "text", "value" => set_value("nachreichenDatum", (isset($geplanter_abschluss[$studiengang->studiengang_kz]) && ($geplanter_abschluss[$studiengang->studiengang_kz] != null)) ? date("d.m.Y", strtotime($geplanter_abschluss[$studiengang->studiengang_kz])) : ""), "class" => "form-control datepicker", "placeholder"=>"DD.MM.YYYY")); ?>
				</div>
			</div>
		</div>
	</div>
    <div class="col-sm-3">
		<div class="form-group">
			<div class="form-group <?php echo (form_error("Maturaze") != "") ? 'has-error' : '' ?>">
				<div class="upload">
					<?php //echo form_input(array('id' => $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ].'_'.$studiengang->studienplaene[0]->studienplan_id, 'name' => 'Maturaze', "type" => "file")); ?>
					<?php echo form_error("Maturaze"); ?>
				</div>
			</div>

			<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>)">Upload</button> -->

			<!-- The fileinput-button span is used to style the file input field as button -->
			<div id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>">
				<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]])) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->nachgereicht == false) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id != null) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->accepted === false)) { ?>
					<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id; ?>, <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>, '<?php echo $studiengang->typ; ?>');" <?php echo (isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt==true) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->accepted ===true)) ? "disabled='disabled'":"";?>><span class="glyphicon glyphicon-trash"></span></button>
                    <a href='<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/".$dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id; ?>' target="_blank">
                        <button type="button" class="btn btn-sm btn-primary">
                            <span class="glyphicon glyphicon-download-alt"></span>
                        </button>
                    </a>
				<?php
				}
				?>
			</div>
			<div id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Upload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="upload-widget" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]) && (($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->nachgereicht == false) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]]->dms_id != null))) ? 'display: none;' : ''; ?>">
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span><?php echo $this->lang->line("requirements_dateiAuswahl"); ?></span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" type="file" name="files[]">
				</span>
			</div>
		</div>
    </div>
</div>

<div id="letztesZeugnis_<?php echo $studiengang->studienplaene[0]->studienplan_id;?>" class="" style="display: none;">
	<div class="row">
		<div class="col-sm-12">
			<?php echo $this->getPhrase("ZGV/letztgueltigesZeugnis", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>
			&nbsp;
			<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->getPhrase("ZGV/letztesZeugnisInfo", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"></span>
		</div>
	</div>
    <div class="row form-upload">
		<div class="col-sm-2">
			<?php echo form_label($personalDocuments[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->bezeichnung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1], "maturazeugnis", array("name" => "Sonst", "for" => "Sonst", "class" => "control-label")) ?>
		</div>
		<?php
		if((isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->mimetype !== null))
		{
			if(isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name))
			{
				if(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name), ".docx") !== false)
				{
					$logo = "docx.gif";
				}
				elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name), ".doc") !== false)
				{
					$logo = "docx.gif";
				}
				elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name), ".pdf") !== false)
				{
					$logo = "document-pdf.svg";
				}
				elseif(strpos(strtolower($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name), ".jpg") !== false)
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
		<div id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="col-sm-1">
			<?php
			if(isset($logo) && ($logo != false))
			{
			?>
			<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
			<?php
			}
			?>
		</div>
		<div class="col-sm-6">
			<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"].'_hochgeladen'; ?>">
				<?php
					if ((!isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]])) || ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->nachgereicht === true))
					{
						echo $this->lang->line('requirements_keinDokHochgeladen');
					}
					else
					{
						echo $dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->name."</br>";
						echo $this->lang->line('requirements_DokHochgeladen');
					}
				?>
				<!-- The global progress bar -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="progress" <?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]) && $dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dms_id !== null) ? 'style="display: none"': '';?>>
					<div class="progress-bar progress-bar-success"></div>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<div class="form-group <?php echo (form_error("Sonst") != "") ? 'has-error' : '' ?>">
					<div class="upload">
						<?php //echo form_input(array('id' => $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"].'_'.$studiengang->studienplaene[0]->studienplan_id, 'name' => 'Sonst', "type" => "file")); ?>
						<?php echo form_error("Sonst"); ?>
					</div>
				</div>

				<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>, true)">Upload</button> -->

				<!-- The fileinput-button span is used to style the file input field as button -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>">
					<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]])) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->nachgereicht == false) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dms_id != null) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->accepted == false)) { ?>
						<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dms_id; ?>, <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>, '<?php echo $studiengang->typ; ?>');" <?php echo (isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt==true) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->accepted ===true)) ? "disabled='disabled'":"";?>><span class="glyphicon glyphicon-trash"></span></button>
                        <a href='<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/".$dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->dms_id; ?>' target="_blank">
                            <button type="button" class="btn btn-sm btn-primary">
                                <span class="glyphicon glyphicon-download-alt"></span>
                            </button>
                        </a>
					<?php
					}
					?>
				</div>
				<div id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Upload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="upload-widget" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]) && ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->nachgereicht == false)) ? 'display: none;' : ''; ?>">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<span><?php echo $this->lang->line("requirements_dateiAuswahl"); ?></span>
						<!-- The file input field used as target for the file upload widget -->
						<input id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" type="file" name="files[]">
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
		$(".fhc-tooltip").tooltip();

		$(".nachreichen_checkbox_zeugnis").on("change", function(evt) {
			var studienplan_id = $(evt.target).attr("studienplan_id");
			toggleDocumentField($(evt.target).prop("checked"), studienplan_id);
			if($(evt.target).prop("checked"))
			{
				$("#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ];?>_nachreichenDatum_"+studienplan_id+"_div").show();
			}
			else
			{
				$("#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ];?>_nachreichenDatum_"+studienplan_id+"_div").hide();
			}
		});

		$(".nachreichen_checkbox_zeugnis").each(function (i, v) {
			var studienplan_id = $(v).attr("studienplan_id");
			toggleDocumentField($(v).prop("checked"), studienplan_id);
			if($(v).prop("checked"))
			{
				$("#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ];?>_nachreichenDatum_"+studienplan_id+"_div").show();
			}
			else
			{
				$("#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ];?>_nachreichenDatum_"+studienplan_id+"_div").hide();
			}

		});

		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/uploadFiles/".$this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]); ?>',
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
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {

				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>, \'<?php echo $studiengang->typ; ?>\');"><span class="glyphicon glyphicon-trash"></span></button>'+
                            '<a href="<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/"; ?>'+data.result.dms_id+'" target="_blank"><button type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-download-alt"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').show();
                    $('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_nachreichenDatum_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>_div').hide();
                    $('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_nachreichenDatum_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').datepicker("setDate", null);
                    $('#letztesZeugnis_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').hide();

					var logo = "";
                    data.result.bezeichnung = data.result.bezeichnung.toLowerCase();
					if(data.result.bezeichnung.indexOf(".docx") !== -1)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".doc") !== -1)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".pdf") !== -1)
					{
						logo = "document-pdf.svg";
					}
					else if(data.result.bezeichnung.indexOf(".jpg") !== -1)
					{
						logo = "document-picture.svg";
					}
					else
					{
						logo = false;
					}

					$("#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>").append('<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>_hochgeladen').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis_".$studiengang->typ]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			},
            drop: function(e, data){
                e.preventDefault();
            },
            dragover: function (e, data)
            {
                e.preventDefault();
            }
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/uploadFiles/".$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]); ?>',
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
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {

				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>, \'<?php echo $studiengang->typ; ?>\');"><span class="glyphicon glyphicon-trash"></span></button>'+
                            '<a href="<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/"; ?>'+data.result.dms_id+'" target="_blank"><button type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-download-alt"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
			$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').show();
					var logo = "";
                    data.result.bezeichnung = data.result.bezeichnung.toLowerCase();
					if(data.result.bezeichnung.indexOf(".docx") !== -1)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".doc") !== -1)
					{
						logo = "docx.gif";
					}
					else if(data.result.bezeichnung.indexOf(".pdf") !== -1)
					{
						logo = "document-pdf.svg";
					}
					else if(data.result.bezeichnung.indexOf(".jpg") !== -1)
					{
						logo = "document-picture.svg";
					}
					else
					{
						logo = false;
					}

					$("#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>").append('<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>_hochgeladen').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			},
            drop: function(e, data){
                e.preventDefault();
            },
            dragover: function (e, data)
            {
                e.preventDefault();
            }
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');


	});

	function toggleDocumentField(isChecked, studienplan_id)
	{
		if(isChecked)
		{
			$("#letztesZeugnis_"+studienplan_id).show();
		}
		else
		{
			$("#letztesZeugnis_"+studienplan_id).hide();
		}
    }
</script>
