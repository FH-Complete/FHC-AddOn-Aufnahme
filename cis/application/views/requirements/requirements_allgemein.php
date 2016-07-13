<legend><?php echo $this->lang->line("requirements_header"); ?></legend>
<?php 
echo form_open_multipart("Requirements/?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id, array("id" => "RequirementsForm", "name" => "RequirementsForm")); ?>
<div class="row">
    <div class="col-sm-12">
        <?php echo $this->getPhrase("ZGV/UploadDiploma", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
    </div>
</div>
<div class="row">
        <div class="col-sm-5">
            <?php echo form_label($this->lang->line('requirements_abschlusszeugnis'), "maturazeugnis", array("name" => "maturazeugnis", "for" => "maturazeugnis", "class" => "control-label")) ?>
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
		    $data = array('id' => 'zeugnis_nachgereicht', 'name' => 'zeugnis_nachgereicht', "checked" => (isset($dokumente["Maturaze"]) && ($dokumente["Maturaze"]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
		    (isset($dokumente["Maturaze"]) && ($dokumente["Maturaze"]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
		    
		    echo form_checkbox($data);
			echo $this->lang->line('requirements_formNachgereicht')
		    ?>			
		</label>
	    </div>
        </div>
        <div class="col-sm-">
            <div class="form-group">
                <div class="form-group <?php echo (form_error("maturazeugnis") != "") ? 'has-error' : '' ?>">
                    <?php echo form_input(array('id' => 'maturazeugnis', 'name' => 'maturazeugnis', "type" => "file")); ?>
                    <?php echo form_error("maturazeugnis"); ?>
                </div>
            </div>
        </div>
    </div>
   
    
    

