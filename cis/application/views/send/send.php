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
	echo "<p class='p'>".$this->getPhrase("Submission/ApplicationReadyForSubmitting", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz)."</p>";
}
else {
	echo "<p class='p'>".$this->getPhrase("Submission/ApplicationNotReadyForSubmitting", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz)."</p>";
	echo "<ul class='list'>";
	foreach ($completenessError as $error=>$value) {
		if ($value === true) {
			echo "<li>".$this->lang->line("send_".$error)."</li>";
		}
	}
	echo "</ul>";

	if (isset($completenessError["dokumente"][$studiengang->studiengang_kz]))
	{
		echo "<p class='p'>".$this->lang->line("send_dokumenteErgaenzen")."</p>";
		echo "<ul class='list'>";
		foreach($completenessError["dokumente"][$studiengang->studiengang_kz] as $error=>$value)
		{
			echo "<li>".$value->bezeichnung_mehrsprachig[$this->session->sprache->index-1]."</li>";
		}
		echo "</ul>";
	}
}
?></fieldset>
	<span id="absenden_text"><?php echo $this->getPhrase("Submission/ApplicationFor", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);?></span>
    <span id="studiengang"><?php echo $studiengang->bezeichnung; ?></span></br>
    <?php echo form_open("Send/send/".$studiengang->studiengang_kz."/".$studiengang->studienplan->studienplan_id, array("id" => "PersonForm", "name" => "PersonForm")); ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
		    <?php if(($prestudentStatus[$studiengang->studiengang_kz]->bewerbung_abgeschicktamum != null) ||(!empty($completenessError)))
{
	echo form_button(array("content"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden button-absenden", "type"=>"submit", "disabled"=>"disabled"));
}
else
{
	echo form_button(array("content"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden button-absenden", "type"=>"submit"));
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
	echo "<p class='p'>".$this->lang->line("send_bereitsAbgeschickt")."</p>";
}
?>
	    </div>
	</div>
    </div>
</div>
