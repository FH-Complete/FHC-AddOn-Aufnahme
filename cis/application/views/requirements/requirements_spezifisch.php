 <legend><?php echo $this->lang->line("requirements_specific_header"); ?></legend>
    <div class="row">
        <div class="col-sm-12">
            <fieldset><?php echo $this->getPhrase("ZGV/SpecificAdmissionRequirements", $sprache); ?></fieldset>
	    <?php
	    foreach($dokumenteStudiengang as $dok)
	    {
	    ?>
	    
	    <div class="row">
		<div class="col-sm-5">
		    <?php echo form_label($this->lang->line('requirements_'.$dok->dokument_kurzbz), $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label")) ?>
		    <div class="form-group">
			<?php
			if((!isset($dokumente[$dok->dokument_kurzbz])) || ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t")) {
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
			    $data = array('id' => $dok->dokument_kurzbz.'_nachgereicht', 'name' => $dok->dokument_kurzbz.'_nachgereicht', "checked" => (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
			    (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;

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
				<?php echo form_input(array('id' => $dok->dokument_kurzbz, 'name' => $dok->dokument_kurzbz, "type" => "file")); ?>
				<?php echo form_error($dok->dokument_kurzbz); ?>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
		
	    <?php		
	    }
	    ?>
        </div>
    </div>