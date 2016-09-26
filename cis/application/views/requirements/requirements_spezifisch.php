<?php
/**
 * ./cis/application/views/requirements/requirements_spezifisch.php
 *
 * @package default
 */
?>
<?php if((isset($dokumenteStudiengang[$studiengang->studiengang_kz])) && (!empty($dokumenteStudiengang))) { ?>
<legend><?php echo $this->lang->line("requirements_specific_header"); ?></legend>
<div class="row">
    <div class="col-sm-12">
		<fieldset><?php echo $this->getPhrase("ZGV/SpecificAdmissionRequirements", $sprache); ?></fieldset>
<?php foreach ($dokumenteStudiengang[$studiengang->studiengang_kz] as $dok) { ?>
		<div class="row">
			<div class="col-sm-5">
				<?php
					//echo form_label($this->lang->line('requirements_'.$dok->dokument_kurzbz), $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label"));
					$p = ($dok->pflicht == 't') ? ' *' : '';

					echo form_label($dok->bezeichnung_mehrsprachig[$this->session->sprache->index-1].$p, $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label"));
				?>
				<div class="form-group" id="<?php echo $dok->dokument_kurzbz.'_hochgeladen'; ?>">
					<?php
						if ((!isset($dokumente[$dok->dokument_kurzbz])) || ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t"))
						{
							echo $this->lang->line('requirements_keinDokHochgeladen');
						}
						else
						{
							echo $this->lang->line('requirements_DokHochgeladen');
						}
					?>
				</div>
				<div class="checkbox">
					<label>
						<?php
							$data = array('id' => $dok->dokument_kurzbz.'_nachgereicht_'.$studiengang->studienplan->studienplan_id, 'class'=>'nachreichen_checkbox', 'name' => $dok->dokument_kurzbz.'_nachgereicht', "checked" => (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
							(isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
							(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
							echo form_checkbox($data);
							echo $this->lang->line('requirements_formNachgereicht')
						?>
					</label>
				</div>
			</div>
			<div class="col-sm-5">
				<div class="form-group">
					<div class="form-group <?php echo (form_error($dok->dokument_kurzbz) != "") ? 'has-error' : '' ?>">
						<div class="upload">
							<?php //echo form_input(array('id' => $dok->dokument_kurzbz."_".$studiengang->studienplan->studienplan_id, 'name' => $dok->dokument_kurzbz, "type" => "file")); ?>
							<?php echo form_error($dok->dokument_kurzbz); ?>
						</div>
					</div>
					<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $dok->dokument_kurzbz; ?>', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button> -->

					<!-- The fileinput-button span is used to style the file input field as button -->
					<div id="<?php echo $dok->dokument_kurzbz; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>">
						<?php if((isset($dokumente[$dok->dokument_kurzbz])) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == "f") && ($dokumente[$dok->dokument_kurzbz]->dms_id != null)) { ?>
							<button type="button" class="btn btn-sm btn-primary icon-trash" onclick="deleteDocument(<?php echo $dokumente[$dok->dokument_kurzbz]->dms_id; ?>, <?php echo $studiengang->studienplan->studienplan_id; ?>);">löschen</button>
						<?php
						}
						?>
					</div>
					<div id="<?php echo $dok->dokument_kurzbz; ?>Upload_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="<?php echo (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == "f")) ? 'display: none;' : ''; ?>">
						<span class="btn btn-success fileinput-button">
							<i class="glyphicon glyphicon-plus"></i>
							<span><?php echo $this->lang->line("requirements_dateiAuswahl"); ?></span>
							<!-- The file input field used as target for the file upload widget -->
							<input id="<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>" type="file" name="files[]">
						</span>
						<br>
						<br>
						<!-- The global progress bar -->
						<div id="<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="progress">
							<div class="progress-bar progress-bar-success"></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-group">
						<div id="<?php echo $dok->dokument_kurzbz; ?>" class="nachreichenDatum">
							<?php echo form_label($this->lang->line('requirements_nachreichenDatum'), "nachreichenDatum", array("name" => "nachreichenDatum", "for" => "nachreichenDatum", "class" => "control-label")) ?>
							<?php echo form_input(array('id' => $dok->dokument_kurzbz.'_nachreichenDatum', 'name' => $dok->dokument_kurzbz.'_nachreichenDatum', 'maxlength' => 64, "type" => "text", "value" => set_value("nachreichenDatum", isset($dokumente[$dok->dokument_kurzbz]) ? date("d.m.Y", strtotime($dokumente[$dok->dokument_kurzbz]->nachgereicht_am)) : ""), "class" => "form-control datepicker")); ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-group">
						<div id="<?php echo $dok->dokument_kurzbz; ?>" class="nachreichenAnmerkung">
							<?php echo form_label($this->lang->line('requirements_nachreichenAnmerkung'), "nachreichenAnmerkung", array("name" => "nachreichenAnmerkung", "for" => "nachreichenAnmerkung", "class" => "control-label")) ?>
							<?php echo form_input(array('id' => $dok->dokument_kurzbz.'_nachreichenAnmerkung', 'name' => $dok->dokument_kurzbz.'_nachreichenAnmerkung', 'maxlength' => 128, "type" => "text", "value" => set_value("nachreichenAnmerkung", isset($dokumente[$dok->dokument_kurzbz]) ? $dokumente[$dok->dokument_kurzbz]->anmerkung : ""), "class" => "form-control")); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

<script type="text/javascript">
	$(document).ready(function() {
		// File upload
		$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/uploadFiles/".$dok->dokument_kurzbz); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
				$('#<?php echo $dok->dokument_kurzbz; ?>_hochgeladen').html("");
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
					data.submit();
				}
			},
			done: function (e, data) {

				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
//					$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').parent().hide();
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>').hide();
					$('#<?php echo $dok->dokument_kurzbz; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary icon-trash" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplan->studienplan_id; ?>);">löschen</button>');
					$('#<?php echo $dok->dokument_kurzbz; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $dok->dokument_kurzbz; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				else
				{
					msg = "Fehler beim Upload";
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $dok->dokument_kurzbz; ?>_hochgeladen').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
		
		<?php if((isset($dokumente[$dok->dokument_kurzbz])) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == "f"))
		{
		?>
			//$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload().prop("disabled", true);
		<?php
		}
		?>
	});
</script>

<?php } ?>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
		$(".nachreichen_checkbox").on("change", function(evt) {
			var studienplan_id = $(evt.target).attr("studienplan_id");
			toggleDateField(studienplan_id);
		});

		$(".datepicker").datepicker({
			dateFormat: "dd.mm.yy",
			minDate: new Date(),
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 10);
				}, 0);
			}
		});
		
		$(".nachreichen_checkbox_zeugnis").each(function(i,v){
			toggleDateField($(v).attr("studienplan_id"));
		});
    });

    function toggleDateField(studienplan_id)
    {
		$(".nachreichenDatum").each(function(i,v) {
			var id = $(v).attr("id");
//			console.log("#"+id+"_nachgereicht_"+studienplan_id);
			if($("#"+id+"_nachgereicht_"+studienplan_id).prop("checked") !== undefined)
			{
				if($("#"+id+"_nachgereicht_"+studienplan_id).prop("checked"))
				{
					$(v).show();
				}
				else
				{
					$(v).hide();
				}
			}
		});

		$(".nachreichenAnmerkung").each(function(i,v)
		{
			var id = $(v).attr("id");
			if($("#"+id+"_nachgereicht_"+studienplan_id).prop("checked") !== undefined)
			{
				if($("#"+id+"_nachgereicht_"+studienplan_id).prop("checked"))
				{
					$(v).show();
				}
				else
				{
					$(v).hide();
				}
			}
		});
    }
</script>
