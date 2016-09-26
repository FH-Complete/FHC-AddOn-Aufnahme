<?php
/**
 * ./cis/application/views/send/send.php
 *
 * @package default
 */


?>
<!-- TODO check if all data is provided -->
<div role="tabpanel" class="tab-pane" id="send">
    <h1 id="sendHeader"><?php echo $this->lang->line("send_header"); ?></h1>
    <!--<fieldset><?php echo $this->lang->line("send_einleitung").'!'; ?></fieldset>-->
    <fieldset><?php
if (empty($completenessError)) {
	echo $this->getPhrase("Submission/ApplicationReadyForSubmitting", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
}
else {
	echo $this->getPhrase("Submission/ApplicationNotReadyForSubmitting", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
	echo "<ul>";
	foreach ($completenessError as $error=>$value) {
		if ($value === true) {
			echo "<li>".$this->lang->line("send_".$error)."</li>";
		}
	}
	echo "</ul>";

	if (isset($completenessError["dokumente"][$studiengang->studiengang_kz]))
	{
		echo $this->lang->line("send_dokumenteErgaenzen");
		echo "<ul>";
		foreach($completenessError["dokumente"][$studiengang->studiengang_kz] as $error=>$value)
		{
			if($value === true)
			{
				echo "<li>".$this->lang->line("send_".$error)."</li>";
			}
		}
		echo "</ul>";
	}
}
?></fieldset>
    <?php echo $studiengang->bezeichnung; ?></br>
    <?php echo form_open("Send/send/".$studiengang->studiengang_kz."/".$studiengang->studienplan->studienplan_id, array("id" => "PersonForm", "name" => "PersonForm")); ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
		    <?php if(($prestudentStatus[$studiengang->studiengang_kz]->bewerbung_abgeschicktamum != null) ||(!empty($completenessError)))
{
	echo form_button(array("content"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit", "disabled"=>"disabled"));
}
else
{
	echo form_button(array("content"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit"));
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
if($prestudentStatus[$studiengang->studiengang_kz]->bewerbung_abgeschicktamum != null)
{
	echo $this->lang->line("send_bereitsAbgeschickt")."</br>";
}
?>
	    </div>
	</div>
    </div>
</div>
