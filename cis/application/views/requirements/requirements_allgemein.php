<legend><?php echo $this->lang->line("requirements_header"); ?></legend>
<?php 
echo form_open_multipart("Requirements/?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id, array("id" => "RequirementsForm", "name" => "RequirementsForm")); ?>
<div class="row">
    <div class="col-sm-12">
	<span><?php echo $this->getPhrase("ZGV/introduction_short", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></span>
	<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->getPhrase("ZGV/introduction_long", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>"></span>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />österreichische Reifeprüfung (AHS, BHS, Berufsreifeprüfung)</label>&nbsp;<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title=""></span>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />Studienberechtigungsprüfung</label>&nbsp;<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title=""></span>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />gleichwertiges ausländisches Zeugnis</label>&nbsp;<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title=""></span>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />einschlägige berufliche Qualifikation (Lehre, BMS) mit Zusatzprüfungen</label>&nbsp;<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title=""></span>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $this->getPhrase("ZGV/UploadDiploma", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
	<!--<?php echo form_label($this->lang->line('requirements_abschlusszeugnis'), "maturazeugnis", array("name" => "Maturaze", "for" => "Maturaze", "class" => "control-label")) ?>-->
	<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis"].'_hochgeladen'; ?>">
	    <?php
	    if((!isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]])) || ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->nachgereicht === "t")) {
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
		$data = array('id' => $this->config->config["dokumentTypen"]["abschlusszeugnis"].'_nachgereicht', 'name' => $this->config->config["dokumentTypen"]["abschlusszeugnis"].'_nachgereicht', "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id, "class"=>"nachreichen_checkbox_zeugnis");
		(isset($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]) && ($dokumente[$this->config->config["dokumentTypen"]["abschlusszeugnis"]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;

		echo form_checkbox($data);
		    echo $this->lang->line('requirements_formNachgereicht')
		?>			
	    </label>
	</div>
    </div>
    <div class="col-sm-5">
	<div class="form-group">
	    <div class="form-group <?php echo (form_error("Maturaze") != "") ? 'has-error' : '' ?>">
		<div class="upload">
		    <?php echo form_input(array('id' => $this->config->config["dokumentTypen"]["abschlusszeugnis"].'_'.$studiengang->studienplan->studienplan_id, 'name' => 'Maturaze', "type" => "file")); ?>
		    <?php echo form_error("Maturaze"); ?>
		</div>
	    </div>
	    <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $this->config->config["dokumentTypen"]["abschlusszeugnis"]; ?>', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button>
	</div>
    </div>
</div>
<div id="letztesZeugnis" class="row" style="display: none;">
    <div class="col-sm-10">
	<?php echo $this->getPhrase("ZGV/letztgueltigesZeugnis", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
	&nbsp;
	<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->getPhrase("ZGV/letztesZeugnisInfo", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>"></span>
    </div>
    <div class="col-sm-5">
	<?php echo form_label($this->lang->line('requirements_letztesZeugnis'), "maturazeugnis", array("name" => "Sonst", "for" => "Sonst", "class" => "control-label")) ?>
	<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"].'_hochgeladen'; ?>">
	    <?php
	    if((!isset($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]])) || ($dokumente[$this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]]->nachgereicht === "t")) {
		echo $this->lang->line('requirements_keinDokHochgeladen');
	     }
	     else
	     {
		 echo $this->lang->line('requirements_DokHochgeladen');
	     }
	     ?>
	</div>
    </div>
    <div class="col-sm-5">
	<div class="form-group">
	    <div class="form-group <?php echo (form_error("Sonst") != "") ? 'has-error' : '' ?>">
		<div class="upload">
		    <?php echo form_input(array('id' => $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"].'_'.$studiengang->studienplan->studienplan_id, 'name' => 'Sonst', "type" => "file")); ?>
		    <?php echo form_error("Sonst"); ?>
		</div>
	    </div>
	    <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('<?php echo $this->config->config["dokumentTypen"]["letztGueltigesZeugnis"]; ?>', <?php echo $studiengang->studienplan->studienplan_id; ?>, true)">Upload</button>
	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {	
	$(".fhc-tooltip").tooltip();
	
	$(".nachreichen_checkbox_zeugnis").on("change", function(evt)
	{
	    toggleDocumentField($(".nachreichen_checkbox_zeugnis").prop("checked"));
	});
	
	$(".nachreichen_checkbox_zeugnis").each(function (i, v)
	{
	    toggleDocumentField($(".nachreichen_checkbox_zeugnis").prop("checked"));
	});
    });
    
    function toggleDocumentField(isChecked)
    {
	if(isChecked)
	{
	    $("#letztesZeugnis").show();
	}
	else
	{
	    $("#letztesZeugnis").hide();
	}
    }
</script>