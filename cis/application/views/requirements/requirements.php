<div role="tabpanel" class="tab-pane" id="requirements">
    <div class="row">
        <div class="col-sm-12">
            <?php echo $this->lang->line("requirements_einleitung"); ?>
        </div>
    </div>
    <legend><?php echo $this->lang->line("requirements_header"); ?></legend>
    <?php echo form_open("Requirements", array("id" => "RequirementsForm", "name" => "RequirementsForm")); ?>
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
    <legend><?php echo $this->lang->line("requirements_specific_header"); ?></legend>
    <div class="row">
        <div class="col-sm-12">
            <fieldset><?php echo $this->lang->line('requirements_specific_einleitung').":"; ?></fieldset>
        </div>
    </div>
    <legend><?php echo $this->lang->line("requirements_motivation_header"); ?></legend>
    <div class="row">
        <div class="col-sm-12">
            <fieldset><?php echo $this->lang->line('requirements_motivation_einleitung1')."!"; ?></fieldset>
            <fieldset><?php echo $this->lang->line('requirements_motivation_einleitung2')."!"; ?></fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
		<?php echo form_submit(array("value"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
	    </div>
	</div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
</div>
