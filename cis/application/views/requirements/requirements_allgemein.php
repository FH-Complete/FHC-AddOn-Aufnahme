<legend><?php echo $this->lang->line("requirements_header"); ?></legend>
<?php echo form_open_multipart("Requirements/?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id, array("id" => "RequirementsForm", "name" => "RequirementsForm")); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group <?php echo (form_error("abschluss") != "") ? 'has-error' : '' ?>">
            <fieldset><?php echo $this->lang->line('requirements_abschluss_header').":"; ?></fieldset>
            <?php echo form_radio(array("id" => "abschluss_matura", "name" => "abschluss"), "matura" , false); ?>
            <span><?php echo $this->lang->line("requirements_abschluss_matura"); ?></span>
            <?php echo form_radio(array("id" => "abschluss_studienberechtigung", "name" => "abschluss"), "studienberechtigung", false); ?>
            <span><?php echo $this->lang->line("requirements_abschluss_studienberechtigung"); ?></span>
            <?php echo form_radio(array("id" => "abschluss_berufsreife", "name" => "abschluss"), "berufsreife", false); ?>
            <span><?php echo $this->lang->line("requirements_abschluss_berufsreife"); ?></span>
            <?php echo form_error("abschluss"); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $this->lang->line('requirements_abschlusszeugnis_header').":"; ?>
    </div>
</div>
<div class="row">
        <div class="col-sm-5">
            <?php echo form_label($this->lang->line('requirements_abschlusszeugnis'), "maturazeugnis", array("name" => "maturazeugnis", "for" => "maturazeugnis", "class" => "control-label")) ?>
            <div class="form-group">
                <?php if(!isset($dokumente["Maturaze"])) {
                    echo $this->lang->line('requirements_keinDokHochgeladen');
                 }
		 else
		 {
		     echo $this->lang->line('requirements_DokHochgeladen');
		 }
                 ?>
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
   
    
    

