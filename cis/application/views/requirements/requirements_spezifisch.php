<?php
/**
 * ./cis/application/views/requirements/requirements_spezifisch.php
 *
 * @package default
 */


?>
<?php if((isset($dokumenteStudiengang[$studiengang->studiengang_kz])) && (!empty($dokumenteStudiengang[$studiengang->studiengang_kz]))) { ?>
<legend><?php echo $this->lang->line("requirements_specific_header"); ?></legend>
<div class="row">
    <div class="col-sm-12">
		<fieldset><?php echo $this->getPhrase("ZGV/SpecificAdmissionRequirements", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></fieldset>
<?php foreach ($dokumenteStudiengang[$studiengang->studiengang_kz] as $dok) { ?>
		<div class="row form-upload">
			<div class="col-sm-4">
				<?php
					//echo form_label($this->lang->line('requirements_'.$dok->dokument_kurzbz), $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label"));
					$p = ($dok->pflicht == true) ? ' *' : '';

					echo form_label($dok->bezeichnung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1].$p, $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label"));
				?>
				<?php
				if((isset($dok->beschreibung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1])) && ($dok->beschreibung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1]!='null'))
				{
				?>
					<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $dok->beschreibung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1];?>"></span>
				<?php
					}
				?>
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
			<div id="<?php echo $dok->dokument_kurzbz; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-5">
				<div class="form-group" >
                    <div id="<?php echo $dok->dokument_kurzbz.'_hochgeladen'; ?>">
                        <?php
                        if ((!isset($dokumente[$dok->dokument_kurzbz])) || ($dokumente[$dok->dokument_kurzbz]->nachgereicht === true))
                        {
                            echo $this->lang->line('requirements_keinDokHochgeladen');
                        }
                        elseif($dokumente[$dok->dokument_kurzbz]->dms_id !== null)
                        {
                            echo $dokumente[$dok->dokument_kurzbz]->name."</br>";
                            echo $this->lang->line('requirements_DokHochgeladen');
                        }
                        ?>
                    </div>
                    <?php
                    ?>
					<!-- The global progress bar -->
					<div id="<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="progress" <?php echo (isset($dokumente[$dok->dokument_kurzbz]) && $dokumente[$dok->dokument_kurzbz]->dms_id !== null) ? 'style="display: none"': '';?>>
						<div class="progress-bar progress-bar-success"></div>
					</div>
				</div>
				<div class="checkbox" style="<?php echo ($dok->nachreichbar == false) ? 'visibility: hidden;' : ''; ?>">
					<label>
						<?php
							$data = array('id' => $dok->dokument_kurzbz.'_nachgereicht_'.$studiengang->studienplaene[0]->studienplan_id, 'class'=>'nachreichen_checkbox', 'name' => $dok->dokument_kurzbz.'_nachgereicht', "checked" => (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht === true)) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplaene[0]->studienplan_id);
							(isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
							(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
							echo form_checkbox($data);
							echo $this->lang->line('requirements_formNachgereicht')
						?>
					</label>
				</div>
				<div class="form-group<?php echo (isset($dokError[$dok->dokument_kurzbz]) && ($dokError[$dok->dokument_kurzbz] == true)) ? " has-error" : ""; ?>">
					<div class="form-group">
						<div id="<?php echo $dok->dokument_kurzbz; ?>_nachreichenDatum_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="nachreichenDatum">
							<?php echo form_label($this->lang->line('requirements_nachreichenDatum'), "nachreichenDatum", array("name" => "nachreichenDatum", "for" => "nachreichenDatum", "class" => "control-label")) ?>
							<?php 
							$data = array('id' => $dok->dokument_kurzbz.'_nachreichenDatum'.$studiengang->studiengang_kz.$studiengang->studienplaene[0]->studienplan_id, 'name' => $dok->dokument_kurzbz.'_nachreichenDatum', 'maxlength' => 64, "type" => "text", "value" => set_value("nachreichenDatum", (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht_am != null)) ? date("d.m.Y", strtotime($dokumente[$dok->dokument_kurzbz]->nachgereicht_am)) : ""), "class" => "form-control datepicker", "placeholder"=>"DD.MM.YYYY");
							(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
							echo form_input($data); ?>
						</div>
					</div>
				</div>
				<div class="form-group<?php echo (isset($dokError[$dok->dokument_kurzbz]) && ($dokError[$dok->dokument_kurzbz] == true)) ? " has-error" : ""; ?>">
					<div class="form-group">
						<div id="<?php echo $dok->dokument_kurzbz; ?>_nachreichenAnmerkung_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="nachreichenAnmerkung">
							<?php echo form_label($this->lang->line('requirements_nachreichenAnmerkung'), "nachreichenAnmerkung", array("name" => "nachreichenAnmerkung", "for" => "nachreichenAnmerkung", "class" => "control-label")) ?>
							<?php 
							$data = array('id' => $dok->dokument_kurzbz.'_nachreichenAnmerkung', 'name' => $dok->dokument_kurzbz.'_nachreichenAnmerkung', 'maxlength' => 128, "type" => "text", "value" => set_value("nachreichenAnmerkung", isset($dokumente[$dok->dokument_kurzbz]) ? $dokumente[$dok->dokument_kurzbz]->anmerkung : ""), "class" => "form-control");
							(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
							echo form_input($data); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<div class="form-group <?php echo (form_error($dok->dokument_kurzbz) != "") ? 'has-error' : '' ?>">
						<div class="upload">
							<?php //echo form_input(array('id' => $dok->dokument_kurzbz."_".$studiengang->studienplaene[0]->studienplan_id, 'name' => $dok->dokument_kurzbz, "type" => "file")); ?>
							<?php echo form_error($dok->dokument_kurzbz); ?>
						</div>
					</div>
					<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $dok->dokument_kurzbz; ?>', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>)">Upload</button> -->

					<!-- The fileinput-button span is used to style the file input field as button -->
					<div id="<?php echo $dok->dokument_kurzbz; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>">
						<?php if((isset($dokumente[$dok->dokument_kurzbz])) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == false) && ($dokumente[$dok->dokument_kurzbz]->dms_id != null) && ($dokumente[$dok->dokument_kurzbz]->accepted ===false)) { ?>
							<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$dok->dokument_kurzbz]->dms_id; ?>, <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>);" <?php echo (isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt==true) && ($dokumente[$dok->dokument_kurzbz]->accepted ===true)) ? "disabled='disabled'":"";?>><span class="glyphicon glyphicon-trash"></span></button>
                            <a href='<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/". $dokumente[$dok->dokument_kurzbz]->dms_id; ?>' target="_blank">
                                <button type="button" class="btn btn-sm btn-primary">
                                    <span class="glyphicon glyphicon-download-alt"></span>
                                </button>
                            </a>
						<?php
						}
						?>
					</div>
					<div id="<?php echo $dok->dokument_kurzbz; ?>Upload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="upload-widget" style="<?php echo (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == false)) ? 'display: none;' : ''; ?>">
						<span class="btn btn-success fileinput-button">
							<i class="glyphicon glyphicon-plus"></i>
							<span><?php echo $this->lang->line("requirements_dateiAuswahl"); ?></span>
							<!-- The file input field used as target for the file upload widget -->
							<input id="<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" type="file" name="files[]">
						</span>
					</div>
				</div>
			</div>
		</div>
<script type="text/javascript">
	$(document).ready(function() {
		// File upload
		$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/uploadFiles/".$dok->dokument_kurzbz); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
			    console.log(e);
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
//					$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').parent().hide();
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').hide();
					$('#<?php echo $dok->dokument_kurzbz; ?>Delete_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>'+
                            '<a href="<?php echo base_url($this->config->config["index_page"])."/Dokumente/download/"; ?>'+data.result.dms_id+'" target="_blank"><button type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-download-alt"></span></button>');
					$('#<?php echo $dok->dokument_kurzbz; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $dok->dokument_kurzbz; ?>_nachgereicht_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').prop("checked", false);
					toggleDateField(<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>);
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
					$('#<?php echo $dok->dokument_kurzbz; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').show();
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

					$("#<?php echo $dok->dokument_kurzbz; ?>_logo_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>").html('<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $dok->dokument_kurzbz; ?>_hochgeladen').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $dok->dokument_kurzbz; ?>Progress_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?> .progress-bar').css(
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
		
		<?php if((isset($dokumente[$dok->dokument_kurzbz])) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht == false))
		{
		?>
			//$('#<?php echo $dok->dokument_kurzbz; ?>FileUpload_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>').fileupload().prop("disabled", true);
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
			},
			onChangeMonthYear: function(year, month, inst)
			{
				$(this).datepicker("setDate", new Date(inst.selectedYear, month-1, inst.currentDay));
			}
			<?php
			if(ucfirst($sprache) === "German")
			{
				?>

					,monthNames: ["Jänner", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"],
					dayNamesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
					dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"]
				<?php
			}
			?>
		});
		
		$(".nachreichen_checkbox_zeugnis").each(function(i,v){
			toggleDateField($(v).attr("studienplan_id"));
		});
    });

    function toggleDateField(studienplan_id)
    {
		$(".nachreichenDatum").each(function(i,v) {
			var id = $(v).attr("id");
			id = id.substring(0, id.indexOf("_nachreichenDatum_"));
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
			id = id.substring(0, id.indexOf("_nachreichenAnmerkung_"));
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
