<legend><?php echo $this->lang->line("requirements_header"); ?></legend>
<?php 
echo form_open_multipart("Requirements/?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id, array("id" => "RequirementsForm", "name" => "RequirementsForm")); ?>
<div class="row">
    <div class="col-sm-12">
	<span><?php echo $this->getPhrase("ZGV/introduction_short", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></span>
	<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->getPhrase("ZGV/introduction_long", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>"></span>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />Allgmeine Hochschule (AHS/BHS)</label>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />Berufsreifeprüfung oder Studienberechtigungsprüfung</label>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />Ausländische Unireife</label>
	</div>
	<div class="radio">
	    <label><input type="radio" name="doktype" value="" />facheinschlägige berufliche Qualifikation</label>
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
	<?php echo form_label($this->lang->line('requirements_abschlusszeugnis'), "maturazeugnis", array("name" => "Maturaze", "for" => "Maturaze", "class" => "control-label")) ?>
	<div class="form-group">
	    <?php
	    if((!isset($dokumente["Maturaze"])) || ($dokumente["Maturaze"]->nachgereicht === "t")) {
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
		$data = array('id' => 'Maturaze_nachgereicht', 'name' => 'Maturaze_nachgereicht', "checked" => (isset($dokumente["Maturaze"]) && ($dokumente["Maturaze"]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
		(isset($dokumente["Maturaze"]) && ($dokumente["Maturaze"]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;

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
		    <?php echo form_input(array('id' => 'Maturaze_'.$studiengang->studienplan->studienplan_id, 'name' => 'Maturaze', "type" => "file")); ?>
		    <?php echo form_error("Maturaze"); ?>
		</div>
	    </div>
	    <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('Maturaze', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button>
	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {	
	$(".fhc-tooltip").tooltip();	
    });
</script>
   
    
    

