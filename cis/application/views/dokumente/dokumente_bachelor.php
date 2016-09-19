<?php
/**
 * ./cis/application/views/dokumente/dokumente_bachelor.php
 *
 * @package default
 */


foreach ($studiengaenge as $stg) {
	if ($stg->typ == "b") {
		echo form_open_multipart("Dokumente/?studiengang_kz=".$stg->studiengang_kz."&studienplan_id=".$stg->studienplan->studienplan_id, array("id" => "DocumentForm", "name" => "DocumentForm"));
?>
    <h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3>
    <div id="<?php echo $stg->studiengang_kz; ?>">
	<?php foreach ($stg->dokumente as $dok) {
?>
	<div class="row">
	    <div class="col-sm-5">
		<?php echo form_label($dok->bezeichnung_mehrsprachig[$this->session->sprache->index-1], $dok->dokument_kurzbz, array("name" => $dok->dokument_kurzbz, "for" => $dok->dokument_kurzbz, "class" => "control-label")) ?>
		<div class="form-group">
		    <?php
			if ((!isset($dokumente[$dok->dokument_kurzbz])) || ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t")) {
				echo $this->lang->line('dokumente_keinDokHochgeladen');
			}
			else {
				echo $this->lang->line('dokumente_DokHochgeladen');
			}
?>
		</div>
		<div class="checkbox">
		    <label>
			<?php
			$data = array('id' => $dok->dokument_kurzbz.'_nachgereicht', 'name' => $dok->dokument_kurzbz.'_nachgereicht', "checked" => (isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$stg->studienplan->studienplan_id);
			(isset($dokumente[$dok->dokument_kurzbz]) && ($dokumente[$dok->dokument_kurzbz]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;

			echo form_checkbox($data);
			echo $this->lang->line('dokumente_formNachgereicht')
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
    <div class="row">
	<div class="col-sm-4">
	    <div class="form-group">
		<?php echo form_button(array("content"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
	    </div>
	</div>
    </div>
    <?php
		echo form_close();
	}
}
