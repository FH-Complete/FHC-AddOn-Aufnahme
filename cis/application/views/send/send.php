<!-- TODO check if all data is provided -->
<div role="tabpanel" class="tab-pane" id="send">
    <h1 id="sendHeader"><?php echo $this->lang->line("send_header"); ?></h1>
    <!--<fieldset><?php echo $this->lang->line("send_einleitung").'!'; ?></fieldset>-->
    <fieldset><?php echo $this->getPhrase("Submission/ApplicationReadyForSubmitting", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);?></fieldset>
    <?php echo $studiengang->bezeichnung; ?></br>
    <?php echo form_open("Send/send/".$studiengang->studiengang_kz."/".$studiengang->studienplan->studienplan_id, array("id" => "PersonForm", "name" => "PersonForm")); ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
		    <?php if($prestudentStatus->bewerbung_abgeschicktamum != null)
		    {
			echo form_submit(array("value"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "disabled"=>"disabled"));
		    }
		    else
		    {
			echo form_submit(array("value"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden"));
		    }
		    ?>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>
    <div class="row">
	<div class="col-sm-6">
	    <div class="form-group">
		<?php
		if($prestudentStatus->bewerbung_abgeschicktamum != null)
		{
		    echo $this->lang->line("send_bereitsAbgeschickt")."</br>";
		}
		?>
	    </div>
	</div>
    </div>
</div>
