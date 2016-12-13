<?php
/**
 * ./cis/application/views/person/person.php
 *
 * @package default
 */

if (!isset($plz)) $plz = null;

?>
<script type="text/javascript">
	function toggleSVNR()
	{
		var code = $('#staatsbuergerschaft option:selected').val();
		if(code === 'A')
		{
			$(".svnr_row").show();
		}
		else
		{
			$(".svnr_row").hide();
		}
	}
    $(document).ready(function() {
		$('#staatsbuergerschaft').on("change", function(){
			toggleSVNR();
		});
		
		toggleSVNR();
		
		$(".datepicker").datepicker({
			dateFormat: "dd.mm.yy",
			maxDate: new Date(),
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 10);
				}, 0);
			},
			onChangeMonthYear: function(year, month, inst)
			{
			    if((year !== inst.currentYear) || (month !== inst.currentMonth))
                {
                    $(this).datepicker("setDate", new Date(inst.selectedYear, month-1, inst.currentDay));
                }
			},
			changeYear: true,
			yearRange: '1900:c'
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

		$(".fhc-tooltip").tooltip();

		$('input[type=file]').on('change', prepareUpload);

		$(".zustelladresse").each(function(i,v) {
			if($(v).prop("checked"))
			{
				var id = $(v).attr("studienplan_id");
				$("#zustelladresse_"+id).show();
			}
		});

		$(".zustelladresse").click(function(event) {
			var id = $(event.currentTarget).attr("studienplan_id");
			if($(event.currentTarget).prop("checked"))
			{
				$("#zustelladresse_"+id).show();
			}
			else
			{
				$("#zustelladresse_"+id).hide();
			}
		});

		$(".adresse_nation").on("change", function(event) {
			toggleAdresse(event.currentTarget);
		});

		$(".zustelladresse_nation").on("change", function(event) {
			toggleZustellAdresse(event.currentTarget);
		});

		$(".plz").on("change", function(event) {
			var plz = $(event.currentTarget).val();
			loadOrtData(plz, $(".ort_dropdown"));
		});

		$(".zustell_plz").on("change", function(event) {
			var plz = $(event.currentTarget).val();
			loadOrtData(plz, $(".zustell_ort_dropdown"));
		});
		
		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/uploadFiles/reisepass"); ?>',
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
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {
				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>').show();
					var logo = "";	
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
					
					$("#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").append('<img class="document_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
					toggleDocumentsComplete(<?php echo $studiengang->studienplan->studienplan_id; ?>);

                    $("#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").parent().removeClass("has-error");
				}
				else
				{
					msg = "Fehler beim Upload";
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/uploadFiles/lebenslauf"); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
				var uploadErrors = [];
				var acceptFileTypes = /^.*\.(jp?g|docx?|pdf)$/i;

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
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {
				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
			$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>').show();
					var logo = "";	
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

					$("#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").append('<img class="document_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
					toggleDocumentsComplete(<?php echo $studiengang->studienplan->studienplan_id; ?>);

                    $("#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").parent().removeClass("has-error");
				}
				else
				{
					msg = "Fehler beim Upload";
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

		toggleAdresse($("#adresse_nation<?php echo $studiengang->studienplan->studienplan_id; ?>"));
		toggleZustellAdresse($("#zustelladresse_nation<?php echo $studiengang->studienplan->studienplan_id; ?>"));
    });

    function toggleAdresse(target)
    {
		//var code = $("#adresse_nation option:selected").val();
		var code = $(target).val();
		if(code === "A")
		{
			hideElement($(".ort_input"));
			showElement($(".ort_dropdown"));
			var plz = $("#plz").val();
			loadOrtData(plz, $(".ort_dropdown"));
		}
		else
		{
			showElement($(".ort_input"));
			hideElement($(".ort_dropdown"));
		}
    }

    function toggleZustellAdresse(target)
    {
		//var code = $("#zustelladresse_nation option:selected").val();
        var code = $(target).val();
		if(code === "A")
		{
			hideElement($(".zustell_ort_input"));
			showElement($(".zustell_ort_dropdown"));
			var plz = $("#zustell_plz").val();
			loadOrtData(plz, $(".zustell_ort_dropdown"));
		}
		else
		{
			showElement($(".zustell_ort_input"));
			hideElement($(".zustell_ort_dropdown"));
		}
    }

    function hideElement(ele)
    {
		$(ele).hide();
    }

    function showElement(ele)
    {
		$(ele).show();
    }

    var files;

    function prepareUpload(event)
    {
		files = event.target.files;
    }
	
	function toggleDocumentsComplete(studienplan_id)
	{		
		if($(".document_logo_"+studienplan_id).length < 2)
		{
			$("#document_incomplete").show();
		}
		else
		{
			$("#document_incomplete").hide();
		}
	}

    function loadOrtData(plz, ele)
    {
        //if($(ele).is(":visible")) {
            $.ajax({
                method: "GET",
                url: "<?php echo base_url($this->config->config["index_page"] . "/codex/Gemeinde/ort"); ?>/" + plz,
                dataType: "json"
            }).done(function (data) {
                if (data.error === 0) {
                    var select = $(ele).find("select");
                    $(select).empty();
                    $.each(data.retval, function (i, v) {
                        if ($(select).attr("name") === "ort_dd") {
                            if (v.gemeinde_id === '<?php echo isset($ort_dd) ? $ort_dd : ""; ?>') {
                                $(ele).find("select").append("<option value='" + v.gemeinde_id + "' selected>" + v.ortschaftsname + "</option>");
                            }
                            else {
                                $(ele).find("select").append("<option value='" + v.gemeinde_id + "'>" + v.ortschaftsname + "</option>");
                            }
                        }
                        else {
                            if (v.gemeinde_id === '<?php echo isset($zustell_ort_dd) ? $zustell_ort_dd : ""; ?>') {
                                $(ele).find("select").append("<option value='" + v.gemeinde_id + "' selected>" + v.ortschaftsname + "</option>");
                            }
                            else {
                                $(ele).find("select").append("<option value='" + v.gemeinde_id + "'>" + v.ortschaftsname + "</option>");
                            }
                        }
                    });
                }
            });
        //}
    }
</script>


<legend>
	<?php echo $this->getPhrase("Personal/Information", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
	<div class="pull-right">
		<span class="incomplete"><?php echo ((isset($complete)) && (!$complete["person"])) ? $this->lang->line("aufnahme/unvollstaendig") : "";?></span>
	</div>
</legend>
<?php echo form_open_multipart("Bewerbung?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id, array("id" => "PersonForm", "name" => "PersonForm")); ?>
	<div class="row form-row">
		<div class="col-sm-3">
			<div class="form-group <?php echo ((form_error("anrede") != "") || (!isset($person->anrede) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formAnrede'), "anrede", array("name" => "anrede", "for" => "anrede", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'anrede', 'name' => 'anrede', "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_dropdown("anrede", array("null" => "", "Herr" => "Herr", "Frau" => "Frau"), isset($person->anrede) ? $person->anrede : "null", $data); ?>
				<?php echo form_error("anrede"); ?>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group <?php echo (form_error("titelpre") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formPrenomen'), "titelpre", array("name" => "titelpre", "for" => "titelpre", "class" => "control-label")) ?>
				<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->lang->line('person_titelPreInfo'); ?>"></span>
				<?php 
				$data = array('id' => 'titelpre', 'name' => 'titelpre', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpre", isset($person->titelpre) ? $person->titelpre : ""), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("titelpre"); ?>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group <?php echo (form_error("titelpost") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formPostnomen'), "titelpost", array("name" => "titelpost", "for" => "titelpost", "class" => "control-label")) ?>
				<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->lang->line('person_titelPostInfo'); ?>"></span>
				<?php 
				$data = array('id' => 'titelpost', 'name' => 'titelpost', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpost", isset($person->titelpost) ? $person->titelpost : ""), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("titelpost"); ?>
			</div>
		</div>
	</div>
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("vorname") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_vorname'), "vorname", array("name" => "vorname", "for" => "vorname", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'vorname', 'name' => 'vorname', 'maxlength' => 32, "type" => "text", "value" => set_value("vorname", isset($person->vorname) ? $person->vorname : ""), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("vorname"); ?>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("nachname") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_nachname'), "nachname", array("name" => "nachname", "for" => "nachname", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'nachname', 'name' => 'nachname', 'maxlength' => 64, "type" => "text", "value" => set_value("nachname", isset($person->nachname) ? $person->nachname : ""), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("nachname"); ?>
			</div>
		</div>
	</div>
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("gebdatum") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_geburtsdatum'), "gebdatum", array("name" => "gebdatum", "for" => "gebdatum", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'gebdatum'.$studiengang->studiengang_kz.$studiengang->studienplan->studienplan_id, 'name' => 'gebdatum', 'maxlength' => 64, "type" => "text", "value" => set_value("gebdatum", isset($person->gebdatum) ? date("d.m.Y", strtotime($person->gebdatum)) : ""), "class" => "form-control datepicker", "placeholder"=>"DD.MM.YYYY");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("gebdatum"); ?>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("geburtsort") != "") || (!isset($person->gebort) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_geburtsort'), "geburtsort", array("name" => "geburtsort", "for" => "geburtsort", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'geburtsort', 'name' => 'geburtsort', "type" => "text", "value" => set_value("geburtsort", (isset($person->gebort) ? $person->gebort : "")), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("geburtsort"); ?>
			</div>
		</div>
	</div>
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("staatsbuergerschaft") != "") || (!isset($person->staatsbuergerschaft) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_staatsbuergerschaft'), "staatsbuergerschaft", array("name" => "staatsbuergerschaft", "for" => "staatsbuergerschaft", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'staatsbuergerschaft', 'name' => 'staatsbuergerschaft', "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_dropdown("staatsbuergerschaft", $nationen, (isset($person->staatsbuergerschaft) ? $person->staatsbuergerschaft : null), $data); ?>
				<?php echo form_error("staatsbuergerschaft"); ?>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("nation") != "") || (!isset($person->geburtsnation) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formGeburtsnation'), "nation", array("name" => "nation", "for" => "nation", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'nation', 'name' => 'nation', "value" => set_value("nation"), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_dropdown("nation", $nationen, (isset($person->geburtsnation) ? $person->geburtsnation : null), $data); ?>
				<?php echo form_error("nation"); ?>
			</div>
		</div>
	</div>
	<div class="row form-row svnr_row">
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("svnr") != "") || (!isset($person->svnr) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formSvn'), "svnr", array("name" => "svnr", "for" => "svnr", "class" => "control-label")) ?>
				<?php echo form_input(array('id' => 'svnr_orig', 'name' => 'svnr_orig', "type" => "hidden", "value" => set_value("svnr", (isset($person->svnr) ? $person->svnr : "")), "class" => "form-control")); ?>
				<?php 
				$data = array('id' => 'svnr', 'name' => 'svnr', "type" => "text", "value" => set_value("svnr", (isset($person->svnr) ? mb_substr($person->svnr, 0, 10) : "")), "class" => "form-control", 'placeholder'=>'XXXXTTMMJJ');
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("svnr"); ?>
			</div>
		</div>
		<!--<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("geschlecht") != "") ? 'has-error' : '' ?>">
				<fieldset><?php echo $this->lang->line('person_geschlecht'); ?></fieldset>
				<?php echo form_radio(array("id" => "geschlecht_m", "name" => "geschlecht"), "m" , (isset($person->geschlecht) && $person->geschlecht=="m") ? true : false); ?>
				<span><?php echo $this->lang->line("person_formMaennlich"); ?></span>
				<?php echo form_radio(array("id" => "geschlecht_f", "name" => "geschlecht"), 'f', (isset($person->geschlecht) && $person->geschlecht=='f') ? true : false); ?>
				<span><?php echo $this->lang->line("person_formWeiblich"); ?></span>
				<?php echo form_error("geschlecht"); ?>
			</div>
		</div>-->
	</div>
	<legend class="">
		<?php echo $this->getPhrase("Personal/Addresse", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
		<div class="pull-right">
			<span class="incomplete"><?php echo ((isset($complete)) && (!$complete["adresse"])) ? $this->lang->line("aufnahme/unvollstaendig") : "";?></span>
		</div>
	</legend>
	<!--<div class="row">
		<div class="col-sm-12">
			<div class="form-group <?php echo (form_error("heimatadresse") != "") ? 'has-error' : '' ?>">
				<fieldset><?php //echo $this->lang->line('person_heimatadresse'); ?></fieldset>
				<?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse", "checked"=>"checked"), null, null); ?>
				<span><?php echo $this->lang->line("person_formHeimatadresseInland"); ?></span>
				<?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse"), null, null); ?>
				<span><?php echo $this->lang->line("person_formHeimatadresseAusland"); ?></span>
				<?php echo form_error("heimatadresse"); ?>
			</div>
		</div>
	</div>-->
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("adresse_nation") != "") || (!isset($adresse->nation) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formAdresseNation'), "adresse_nation", array("name" => "adresse_nation", "for" => "adresse_nation", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'adresse_nation'.$studiengang->studienplan->studienplan_id, 'name' => 'adresse_nation', "value" => set_value("adresse_nation"), "class" => "form-control adresse_nation");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_dropdown("adresse_nation", $nationen, (isset($adresse->nation) ? $adresse->nation : null), $data); ?>
				<?php echo form_error("adresse_nation"); ?>
			</div>
		</div>
	</div>
	<!--<div class="row">
	<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("plzOrt") != "") || (!isset($gemeinde_id) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formPlzOrt'), "plzOrt", array("name" => "plzOrt", "for" => "plzOrt", "class" => "control-label")) ?>
				<?php echo form_dropdown("plzOrt", $plz, (isset($gemeinde_id) ? $gemeinde_id : null), array('id' => 'plzOrt', 'name' => 'plzOrt', "value" => set_value("plzOrt"), "class" => "form-control")); ?>
				<?php echo form_error("plzOrt"); ?>
			</div>
		</div>
	</div>-->
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("strasse") != "") || (!isset($adresse->strasse) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_strasse'), "strasse", array("name" => "strasse", "for" => "strasse", "class" => "control-label")) ?>
				<?php
				$data = array('id' => 'strasse', 'name' => 'strasse', "type" => "text", "value" => set_value("strasse", (isset($adresse->strasse) ? $adresse->strasse : NULL)), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("strasse"); ?>
			</div>
		</div>
	</div>
	<div class="row form-row">
		<div class="col-sm-3">
			<div class="form-group <?php echo ((form_error("plz") != "") || (!isset($adresse->plz) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formPlz'), "plz", array("name" => "plz", "for" => "plz", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'plz', 'name' => 'plz', "type" => "text", "value" => set_value("plz", (isset($adresse->plz) ? $adresse->plz : NULL)), "class" => "form-control plz");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("plz"); ?>
			</div>
		</div>
		<div id="ort_input" class="col-sm-6 ort_input" style="display: none;">
			<div class="form-group <?php echo ((form_error("ort") != "") || (!isset($adresse->ort) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formOrt'), "ort", array("name" => "ort", "for" => "ort", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'ort', 'name' => 'ort', "type" => "text", "value" => set_value("ort", (isset($adresse->ort) ? $adresse->ort : NULL)), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("ort"); ?>
			</div>
		</div>
		<div id="ort_dropdown" class="col-sm-6 ort_dropdown" style="display: none;">
			<div class="form-group <?php echo ((form_error("ort") != "" ) || (!isset($adresse->ort) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formOrt'), "ort", array("name" => "ort", "for" => "ort", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'ort', 'name' => 'ort_dd', "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_dropdown("ort_dd", null, (isset($ort_dd) ? $ort_dd : NULL), $data); ?>
				<?php echo form_error("ort"); ?>
			</div>
		</div>
	</div>
	<!--<div class="row">
		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("bundesland") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formBundesland'), "bundesland", array("name" => "bundesland", "for" => "bundesland", "class" => "control-label")) ?>
				<?php echo form_dropdown("bundesland", $bundeslaender, (isset($person->bundesland_code) ? $person->bundesland_code : NULL), array('id' => 'bundesland', 'name' => 'bundesland', "class" => "form-control")); ?>
				<?php echo form_error("bundesland"); ?>
			</div>
		</div>
	</div>-->
	<div class="row form-row">
		<div class="col-sm-12">
			<div class="form-group <?php echo (form_error("zustelladresse") != "") ? 'has-error' : '' ?>">
				<div class="checkbox">
					<label>
						<?php
							$data = array('id' => 'zustelladresse', 'name' => 'zustelladresse', "checked" => isset($zustell_adresse) ? TRUE : FALSE, "class"=>"zustelladresse", "studienplan_id"=>$studiengang->studienplan->studienplan_id);
							(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
							echo form_checkbox($data);
							echo $this->getPhrase("Personal/DifferentAddress", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
						?>
					</label>
				</div>
				<?php echo form_error("zustelladresse"); ?>
			</div>
		</div>
	</div>
	<div id="zustelladresse_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="display: none;">
		<legend class="">
			<?php echo $this->getPhrase("Personal/Zustelladresse", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
			<div class="pull-right">
				<span class="incomplete"><?php echo ((isset($complete)) && (!$complete["zustelladresse"])) ? $this->lang->line("aufnahme/unvollstaendig") : "";?></span>
			</div>
		</legend>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo ((form_error("zustelladresse_nation") != "") || (!isset($zustell_adresse->nation) && $incomplete)) ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formAdresseNation'), "zustelladresse_nation", array("name" => "zustelladresse_nation", "for" => "zustelladresse_nation", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'zustelladresse_nation'.$studiengang->studienplan->studienplan_id, 'name' => 'zustelladresse_nation', "value" => set_value("zustelladresse_nation"), "class" => "form-control zustelladresse_nation");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("zustelladresse_nation", $nationen, (isset($zustell_adresse->nation) ? $zustell_adresse->nation : null), $data); ?>
					<?php echo form_error("zustelladresse_nation"); ?>
				</div>
			</div>
		</div>
		<!--<div class="row">
			<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("zustell_plzOrt") != "") || (!isset($zustell_gemeinde_id) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_formPlzOrt'), "zustell_plzOrt", array("name" => "zustell_plzOrt", "for" => "zustell_plzOrt", "class" => "control-label")) ?>
				<?php echo form_dropdown("zustell_plzOrt", $plz, (isset($zustell_gemeinde_id) ? $zustell_gemeinde_id : null), array('id' => 'zustell_plzOrt', 'name' => 'zustell_plzOrt', "value" => set_value("zustell_plzOrt"), "class" => "form-control")); ?>
				<?php echo form_error("zustell_plzOrt"); ?>
			</div>
			</div>
		</div>-->
		<div class="row form-row">
			<div class="col-sm-8">
				<div class="form-group <?php echo ((form_error("zustell_strasse") != "") || (!isset($zustell_adresse->strasse) && $incomplete)) ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_strasse'), "zustell_strasse", array("name" => "zustell_strasse", "for" => "zustell_strasse", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'zustell_strasse', 'name' => 'zustell_strasse', "type" => "text", "value" => set_value("zustell_strasse", (isset($zustell_adresse->strasse) ? $zustell_adresse->strasse : NULL)), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("zustell_strasse"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-3">
				<div class="form-group <?php echo ((form_error("zustell_plz") != "") || (!isset($zustell_adresse->plz) && $incomplete)) ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPlz'), "zustell_plz", array("name" => "zustell_plz", "for" => "zustell_plz", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'zustell_plz', 'name' => 'zustell_plz', "type" => "text", "value" => set_value("zustell_plz", (isset($zustell_adresse->plz) ? $zustell_adresse->plz : NULL)), "class" => "form-control zustell_plz");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("zustell_plz"); ?>
				</div>
			</div>
			<div id="zustell_ort_input" class="col-sm-6 zustell_ort_input" style="display: none;">
				<div class="form-group <?php echo ((form_error("zustell_ort") != "")  || (!isset($zustell_adresse->ort) && $incomplete)) ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formOrt'), "zustell_ort", array("name" => "zustell_ort", "for" => "zustell_ort", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'zustell_ort', 'name' => 'zustell_ort', "type" => "text", "value" => set_value("zustell_ort", (isset($zustell_adresse->ort) ? $zustell_adresse->ort : NULL)), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("zustell_ort"); ?>
				</div>
			</div>
			<div id="zustell_ort_dropdown" class="col-sm-6 zustell_ort_dropdown" style="display: none;">
				<div class="form-group <?php echo ((form_error("zustell_ort") != "") || (!isset($zustell_adresse->ort) && $incomplete)) ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formOrt'), "zustell_ort", array("name" => "zustell_ort", "for" => "zustell_ort", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'zustell_ort', 'name' => 'zustell_ort_dd', "value" => set_value("zustell_ort"), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("zustell_ort_dd", null, null, $data); ?>
					<?php echo form_error("zustell_ort"); ?>
				</div>
			</div>
		</div>
	</div>
	<legend class="">
		<?php echo $this->getPhrase("Personal/Kontakt", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
		<div class="pull-right">
			<span class="incomplete"><?php echo ((isset($complete)) && (!$complete["kontakt"])) ? $this->lang->line("aufnahme/unvollstaendig") : "";?></span>
		</div>
	</legend>
	<div class="row form-row">
		<div class="col-sm-6">
			<div class="form-group <?php echo ((form_error("telefon") != "")  || (!isset($kontakt["telefon"]) && $incomplete)) ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_telefon'), "telefon", array("name" => "telefon", "for" => "telefon", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'telefon', 'name' => 'telefon', "type" => "text", "value" => set_value("telefon", isset($kontakt["telefon"]) ? $kontakt["telefon"]->kontakt : "" ), "class" => "form-control", "placeholder"=>"0664 1234213");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("telefon"); ?>
			</div>
		</div>
<!--		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("fax") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_fax'), "fax", array("name" => "fax", "for" => "fax", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'fax', 'name' => 'fax', "type" => "text", "value" => set_value("fax", isset($kontakt["fax"]) ? $kontakt["fax"]->kontakt : ""), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("fax"); ?>
			</div>
		</div>-->
		<div class="col-sm-6">
			<div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
				<?php echo form_label($this->lang->line('person_emailAdresse'), "email", array("name" => "email", "for" => "email", "class" => "control-label")) ?>
				<?php 
				$data = array('id' => 'email', 'name' => 'email', "type" => "email", "value" => set_value("email", isset($kontakt["email"]) ? $kontakt["email"]->kontakt : "" ), "class" => "form-control");
				(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
				echo form_input($data); ?>
				<?php echo form_error("email"); ?>
			</div>
		</div>
	</div>
	<legend class="">
		<?php echo $this->getPhrase("Personal/DokumentenUpload", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
		<div class="pull-right">
			<span id="document_incomplete" class="incomplete"><?php echo ((isset($complete)) && (!$complete["dokumente"])) ? $this->lang->line("aufnahme/unvollstaendig") : "";?></span>
		</div>
	</legend>
	<div class="row form-row">
		<div class="col-sm-12">
			<div class="form-group">
				<?php echo $this->getPhrase("Personal/PleaseUploadDocuments", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);  ?>
			</div>
		</div>
	</div>
	<div class="row form-upload <?php echo ((!isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && $incomplete)) ? 'has-error' : '' ?>">
		<div class="col-sm-2">
			<?php echo form_label($personalDocuments[$this->config->config["dokumentTypen"]["reisepass"]]->bezeichnung_mehrsprachig[$this->session->sprache->index-1]."*", "reisepass", array("name" => "reisepass", "for" => "reisepass", "class" => "control-label")) ?>
		</div>
		<?php
		if(isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->mimetype))
		{
			if(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".docx") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".doc") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".pdf") !== false)
			{
				$logo = "document-pdf.svg";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".jpg") !== false)
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
			$logo = "";
		}
		?>
		<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="col-sm-1">
			<?php 
			if(isset($logo) && ($logo != false))
			{
			?>
			<img class="document_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
			<?php
			}
			?>
		</div>
		<div class="col-sm-6">
			<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>">
				<?php
					if ((!isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]])) || ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht === true))
					{
						echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
					}
					else
					{
						echo $dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name."</br>";
						echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
					}
				?>
				<!-- The global progress bar -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="progress">
					<div class="progress-bar progress-bar-success"></div>
				</div>
			</div>
		<!--<div class="checkbox">
		<label>
			<?php
				$data = array('id' => 'reisepass_nachgereicht', 'name' => 'reisepass_nachgereicht', "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht === true)) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
				(isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
				echo form_checkbox($data);
				echo $this->lang->line('person_formNachgereicht')
			?>
		</label>
		</div>-->
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<div class="form-group <?php echo (form_error("reisepass") != "") ? 'has-error' : '' ?>">
					<div class="upload">
						<?php
							//echo form_input(array('id' => 'reisepass_'.$studiengang->studienplan->studienplan_id, 'name' => 'reisepass', "type" => "file"));
							echo form_error("reisepass");
						?>
					</div>
				</div>
			</div>
			<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('reisepass', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button> -->

			<!-- The fileinput-button span is used to style the file input field as button -->
			<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>">
				<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]])) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht == false) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id != null) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->accepted == false)) { ?>
					<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id; ?>, <?php echo $studiengang->studienplan->studienplan_id; ?>);" <?php echo (isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt==true) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->accepted === true)) ? "disabled='disabled'":"";?> ><span class="glyphicon glyphicon-trash"></span></button>
				<?php
				}
				?>
			</div>
			<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Upload_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="upload-widget" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht == false)) ? 'display: none;' : ''; ?>">
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span><?php echo $this->lang->line("aufnahme_dateiAuswahl"); ?></span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>" type="file" name="files[]">
				</span>
			</div>
		</div>
	</div>
	<div class="row form-upload <?php echo ((!isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && $incomplete)) ? 'has-error' : '' ?>">
		<div class="col-sm-2">
			<?php echo form_label($personalDocuments[$this->config->config["dokumentTypen"]["lebenslauf"]]->bezeichnung_mehrsprachig[$this->session->sprache->index-1]."*"."&nbsp;", "lebenslauf", array("name" => "lebenslauf", "for" => "lebenslauf", "class" => "control-label")) ?><span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="inklusive Foto"></span>
		</div>
		<?php
		if((isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype !== null))
		{
			if(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".docx") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".doc") !== false)
			{
				$logo = "docx.gif";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".pdf") !== false)
			{
				$logo = "document-pdf.svg";
			}
			elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".jpg") !== false)
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
			$logo = "";
		}
		?>
		<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="col-sm-1">
			<?php 
			if(isset($logo) && ($logo != false))
			{
			?>
			<img class="document_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
			<?php
			}
			?>
		</div>
		<div class="col-sm-6">

			<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>">
				<?php
					if ((!isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]])) || ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht === true))
					{
						echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
					}
					else
					{
						echo $dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name."</br>";
						echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
					}
				?>
				<!-- The global progress bar -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="progress">
					<div class="progress-bar progress-bar-success"></div>
				</div>
			</div>
		<!--<div class="checkbox">
		<label>
			<?php
				$data = array('id' => 'lebenslauf_nachgereicht', 'name' => 'lebenslauf_nachgereicht', "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht === true)) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
				(isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
				echo form_checkbox($data);
				echo $this->lang->line('person_formNachgereicht')
			?>
		</label>
		</div>-->
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<div class="form-group <?php echo (form_error("lebenslauf") != "") ? 'has-error' : '' ?>">
					<div class="upload">
						<?php
							//echo form_input(array('id' => 'lebenslauf_'.$studiengang->studienplan->studienplan_id, 'name' => 'lebenslauf', "type" => "file"));
							echo form_error("lebenslauf");
						?>
					</div>
				</div>
			</div>
			<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('lebenslauf', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button> -->

			<!-- The fileinput-button span is used to style the file input field as button -->
			<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>">
				<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]])) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht == false) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id != null) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->accepted == false)) { ?>
				<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id; ?>, <?php echo $studiengang->studienplan->studienplan_id; ?>);" <?php echo (isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt==true) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->accepted === true)) ? "disabled='disabled'":"";?>><span class="glyphicon glyphicon-trash"></span></button>
				<?php
				}
				?>
			</div>
			<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Upload_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="upload-widget" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht == false)) ? 'display: none;' : ''; ?>">
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span><?php echo $this->lang->line("aufnahme_dateiAuswahl"); ?></span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>" type="file" name="files[]">
				</span>
			</div>
		</div>
	</div>