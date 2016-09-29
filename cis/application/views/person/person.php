<?php
/**
 * ./cis/application/views/person/person.php
 *
 * @package default
 */

if (!isset($plz)) $plz = null;

?>

<div role="tabpanel" class="tab-pane" id="daten">
    <legend>
		<?php echo $this->getPhrase("Personal/Information", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
	</legend>
    <?php echo form_open_multipart("Bewerbung?studiengang_kz=".$studiengang->studiengang_kz, array("id" => "PersonForm", "name" => "PersonForm")); ?>
		<div class="row form-row">
			<div class="col-sm-3">
				<div class="form-group <?php echo (form_error("anrede") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formAnrede'), "anrede", array("name" => "anrede", "for" => "anrede", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'anrede', 'name' => 'anrede', "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("anrede", array("Herr" => "Herr", "Frau" => "Frau"), isset($person->anrede) ? $person->anrede : "Herr", $data); ?>
					<?php echo form_error("anrede"); ?>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group <?php echo (form_error("titelpre") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPrenomen'), "titelpre", array("name" => "titelpre", "for" => "titelpre", "class" => "control-label")) ?>
					<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->lang->line('person_titelPreInfo'); ?>"></span>
					<?php 
					$data = array('id' => 'titelpre', 'name' => 'titelpre', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpre", isset($person->titelpre) ? $person->titelpre : ""), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("titelpre"); ?>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group <?php echo (form_error("titelpost") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPostnomen'), "titelpost", array("name" => "titelpost", "for" => "titelpost", "class" => "control-label")) ?>
					<span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="<?php echo $this->lang->line('person_titelPostInfo'); ?>"></span>
					<?php 
					$data = array('id' => 'titelpost', 'name' => 'titelpost', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpost", isset($person->titelpost) ? $person->titelpost : ""), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("titelpost"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("vorname") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_vorname'), "vorname", array("name" => "vorname", "for" => "vorname", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'vorname', 'name' => 'vorname', 'maxlength' => 32, "type" => "text", "value" => set_value("vorname", isset($person->vorname) ? $person->vorname : ""), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("vorname"); ?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("nachname") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_nachname'), "nachname", array("name" => "nachname", "for" => "nachname", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'nachname', 'name' => 'nachname', 'maxlength' => 64, "type" => "text", "value" => set_value("nachname", isset($person->nachname) ? $person->nachname : ""), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("nachname"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("gebdatum") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_geburtsdatum'), "gebdatum", array("name" => "gebdatum", "for" => "gebdatum", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'gebdatum', 'name' => 'gebdatum', 'maxlength' => 64, "type" => "date", "value" => set_value("gebdatum", isset($person->gebdatum) ? date("d.m.Y", strtotime($person->gebdatum)) : ""), "class" => "form-control datepicker");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("gebdatum"); ?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("geburtsort") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_geburtsort'), "geburtsort", array("name" => "geburtsort", "for" => "geburtsort", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'geburtsort', 'name' => 'geburtsort', "type" => "text", "value" => set_value("geburtsort", (isset($person->gebort) ? $person->gebort : "")), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("geburtsort"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("staatsbuergerschaft") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_staatsbuergerschaft'), "staatsbuergerschaft", array("name" => "staatsbuergerschaft", "for" => "staatsbuergerschaft", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'staatsbuergerschaft', 'name' => 'staatsbuergerschaft', "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("staatsbuergerschaft", $nationen, (isset($person->staatsbuergerschaft) ? $person->staatsbuergerschaft : "A"), $data); ?>
					<?php echo form_error("staatsbuergerschaft"); ?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("nation") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formGeburtsnation'), "nation", array("name" => "nation", "for" => "nation", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'nation', 'name' => 'nation', "value" => set_value("nation"), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("nation", $nationen, (isset($person->geburtsnation) ? $person->geburtsnation : "A"), $data); ?>
					<?php echo form_error("nation"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("svnr") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formSvn'), "svnr", array("name" => "svnr", "for" => "svnr", "class" => "control-label")) ?>
					<?php echo form_input(array('id' => 'svnr_orig', 'name' => 'svnr_orig', "type" => "hidden", "value" => set_value("svnr", (isset($person->svnr) ? $person->svnr : "")), "class" => "form-control")); ?>
					<?php 
					$data = array('id' => 'svnr', 'name' => 'svnr', "type" => "text", "value" => set_value("svnr", (isset($person->svnr) ? mb_substr($person->svnr, 0, 10) : "")), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("svnr"); ?>
				</div>
			</div>
			<!--<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("geschlecht") != "") ? 'has-error' : '' ?>">
					<fieldset><?php echo $this->lang->line('person_geschlecht'); ?></fieldset>
					<?php echo form_radio(array("id" => "geschlecht_m", "name" => "geschlecht"), "m" , (isset($person->geschlecht) && $person->geschlecht=="m") ? true : false); ?>
					<span><?php echo $this->lang->line("person_formMaennlich"); ?></span>
					<?php echo form_radio(array("id" => "geschlecht_f", "name" => "geschlecht"), "f", (isset($person->geschlecht) && $person->geschlecht=="f") ? true : false); ?>
					<span><?php echo $this->lang->line("person_formWeiblich"); ?></span>
					<?php echo form_error("geschlecht"); ?>
				</div>
			</div>-->
		</div>
		<legend class="">
			<?php echo $this->getPhrase("Personal/Addresse", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
		</legend>
		<!--<div class="row">
			<div class="col-sm-12">
				<div class="form-group <?php echo (form_error("heimatadresse") != "") ? 'has-error' : '' ?>">
					<fieldset><?php //echo $this->lang->line('person_heimatadresse'); ?></fieldset>
					<?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse", "checked"=>"checked"), null, null); ?>
					<span><?php echo sprintf($this->lang->line("person_formHeimatadresse"), "Inland"); ?></span>
					<?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse"), null, null); ?>
					<span><?php echo sprintf($this->lang->line("person_formHeimatadresse"), "Ausland"); ?></span>
					<?php echo form_error("heimatadresse"); ?>
				</div>
			</div>
		</div>-->
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("adresse_nation") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formAdresseNation'), "adresse_nation", array("name" => "adresse_nation", "for" => "adresse_nation", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'adresse_nation', 'name' => 'adresse_nation', "value" => set_value("adresse_nation"), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("adresse_nation", $nationen, (isset($adresse->nation) ? $adresse->nation : "A"), $data); ?>
					<?php echo form_error("adresse_nation"); ?>
				</div>
			</div>
		</div>
		<!--<div class="row">
		<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("plzOrt") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPlzOrt'), "plzOrt", array("name" => "plzOrt", "for" => "plzOrt", "class" => "control-label")) ?>
					<?php echo form_dropdown("plzOrt", $plz, (isset($gemeinde_id) ? $gemeinde_id : null), array('id' => 'plzOrt', 'name' => 'plzOrt', "value" => set_value("plzOrt"), "class" => "form-control")); ?>
					<?php echo form_error("plzOrt"); ?>
				</div>
			</div>
		</div>-->
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("strasse") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_strasse'), "strasse", array("name" => "strasse", "for" => "strasse", "class" => "control-label")) ?>
					<?php
					$data = array('id' => 'strasse', 'name' => 'strasse', "type" => "text", "value" => set_value("strasse", (isset($adresse->strasse) ? $adresse->strasse : NULL)), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("strasse"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-3">
				<div class="form-group <?php echo (form_error("plz") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPlz'), "plz", array("name" => "plz", "for" => "plz", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'plz', 'name' => 'plz', "type" => "text", "value" => set_value("plz", (isset($adresse->plz) ? $adresse->plz : NULL)), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("plz"); ?>
				</div>
			</div>
			<div id="ort_input" class="col-sm-6" style="display: none;">
				<div class="form-group <?php echo (form_error("ort") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formOrt'), "ort", array("name" => "ort", "for" => "ort", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'ort', 'name' => 'ort', "type" => "text", "value" => set_value("ort", (isset($adresse->ort) ? $adresse->ort : NULL)), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("ort"); ?>
				</div>
			</div>
			<div id="ort_dropdown" class="col-sm-6" style="display: none;">
				<div class="form-group <?php echo (form_error("ort") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formOrt'), "ort", array("name" => "ort", "for" => "ort", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'ort', 'name' => 'ort_dd', "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_dropdown("ort_dd", null, (isset($ort_dd) ? $ort_dd : NULL), $data); ?>
					<?php echo form_error("ort"); ?>
				</div>
			</div>
		</div>
		<!--<div class="row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("bundesland") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formBundesland'), "bundesland", array("name" => "bundesland", "for" => "bundesland", "class" => "control-label")) ?>
					<?php echo form_dropdown("bundesland", $bundeslaender, (isset($person->bundesland_code) ? $person->bundesland_code : NULL), array('id' => 'bundesland', 'name' => 'bundesland', "class" => "form-control")); ?>
					<?php echo form_error("bundesland"); ?>
				</div>
			</div>
		</div>-->
		<div class="row form-row">
			<div class="col-sm-12">
				<div class="form-group <?php echo (form_error("zustelladresse") != "") ? 'has-error' : '' ?>">
					<div class="checkbox">
						<label>
							<?php
								$data = array('id' => 'zustelladresse', 'name' => 'zustelladresse', "checked" => isset($zustell_adresse) ? TRUE : FALSE, "class"=>"zustelladresse", "studienplan_id"=>$studiengang->studienplan->studienplan_id);
								(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
								echo form_checkbox($data);
								echo $this->getPhrase("Personal/DifferentAddress", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
							?>
						</label>
					</div>
					<?php echo form_error("zustelladresse"); ?>
				</div>
			</div>
		</div>
		<div id="zustelladresse_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="display: none;">
			<legend class=""><?php echo $this->getPhrase("Personal/Zustelladresse", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></legend>
			<div class="row form-row">
				<div class="col-sm-6">
					<div class="form-group <?php echo (form_error("zustelladresse_nation") != "") ? 'has-error' : '' ?>">
						<?php echo form_label($this->lang->line('person_formAdresseNation'), "zustelladresse_nation", array("name" => "zustelladresse_nation", "for" => "zustelladresse_nation", "class" => "control-label")) ?>
						<?php 
						$data = array('id' => 'zustelladresse_nation', 'name' => 'zustelladresse_nation', "value" => set_value("zustelladresse_nation"), "class" => "form-control");
						(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
						echo form_dropdown("zustelladresse_nation", $nationen, (isset($zustell_adresse->nation) ? $zustell_adresse->nation : "A"), $data); ?>
						<?php echo form_error("zustelladresse_nation"); ?>
					</div>
				</div>
			</div>
			<!--<div class="row">
				<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("zustell_plzOrt") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_formPlzOrt'), "zustell_plzOrt", array("name" => "zustell_plzOrt", "for" => "zustell_plzOrt", "class" => "control-label")) ?>
					<?php echo form_dropdown("zustell_plzOrt", $plz, (isset($zustell_gemeinde_id) ? $zustell_gemeinde_id : null), array('id' => 'zustell_plzOrt', 'name' => 'zustell_plzOrt', "value" => set_value("zustell_plzOrt"), "class" => "form-control")); ?>
					<?php echo form_error("zustell_plzOrt"); ?>
				</div>
				</div>
			</div>-->
			<div class="row form-row">
				<div class="col-sm-8">
					<div class="form-group <?php echo (form_error("zustell_strasse") != "") ? 'has-error' : '' ?>">
						<?php echo form_label($this->lang->line('person_strasse'), "zustell_strasse", array("name" => "zustell_strasse", "for" => "zustell_strasse", "class" => "control-label")) ?>
						<?php 
						$data = array('id' => 'zustell_strasse', 'name' => 'zustell_strasse', "type" => "text", "value" => set_value("zustell_strasse", (isset($zustell_adresse->strasse) ? $zustell_adresse->strasse : NULL)), "class" => "form-control");
						(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
						echo form_input($data); ?>
						<?php echo form_error("zustell_strasse"); ?>
					</div>
				</div>
			</div>
			<div class="row form-row">
				<div class="col-sm-3">
					<div class="form-group <?php echo (form_error("zustell_plz") != "") ? 'has-error' : '' ?>">
						<?php echo form_label($this->lang->line('person_formPlz'), "zustell_plz", array("name" => "zustell_plz", "for" => "zustell_plz", "class" => "control-label")) ?>
						<?php
						$data = array('id' => 'zustell_plz', 'name' => 'zustell_plz', "type" => "text", "value" => set_value("zustell_plz", (isset($zustell_adresse->plz) ? $zustell_adresse->plz : NULL)), "class" => "form-control");
						(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
						echo form_input($data); ?>
						<?php echo form_error("zustell_plz"); ?>
					</div>
				</div>
				<div id="zustell_ort_input" class="col-sm-6" style="display: none;">
					<div class="form-group <?php echo (form_error("zustell_ort") != "") ? 'has-error' : '' ?>">
						<?php echo form_label($this->lang->line('person_formOrt'), "zustell_ort", array("name" => "zustell_ort", "for" => "zustell_ort", "class" => "control-label")) ?>
						<?php 
						$data = array('id' => 'zustell_ort', 'name' => 'zustell_ort', "type" => "text", "value" => set_value("zustell_ort", (isset($zustell_adresse->ort) ? $zustell_adresse->ort : NULL)), "class" => "form-control");
						(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
						echo form_input($data); ?>
						<?php echo form_error("zustell_ort"); ?>
					</div>
				</div>
				<div id="zustell_ort_dropdown" class="col-sm-6" style="display: none;">
					<div class="form-group <?php echo (form_error("zustell_ort") != "") ? 'has-error' : '' ?>">
						<?php echo form_label($this->lang->line('person_formOrt'), "zustell_ort", array("name" => "zustell_ort", "for" => "zustell_ort", "class" => "control-label")) ?>
						<?php 
						$data = array('id' => 'zustell_ort', 'name' => 'zustell_ort_dd', "value" => set_value("zustell_ort"), "class" => "form-control");
						(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
						echo form_dropdown("zustell_ort_dd", null, null, $data); ?>
						<?php echo form_error("zustell_ort"); ?>
					</div>
				</div>
			</div>
		</div>
		<legend class=""><?php echo $this->getPhrase("Personal/Kontakt", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></legend>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("telefon") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_telefon'), "telefon", array("name" => "telefon", "for" => "telefon", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'telefon', 'name' => 'telefon', "type" => "text", "value" => set_value("telefon", isset($kontakt["telefon"]) ? $kontakt["telefon"]->kontakt : "" ), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("telefon"); ?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("fax") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_fax'), "fax", array("name" => "fax", "for" => "fax", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'fax', 'name' => 'fax', "type" => "text", "value" => set_value("fax", isset($kontakt["fax"]) ? $kontakt["fax"]->kontakt : ""), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("fax"); ?>
				</div>
			</div>
		</div>
		<div class="row form-row">
			<div class="col-sm-6">
				<div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
					<?php echo form_label($this->lang->line('person_emailAdresse'), "email", array("name" => "email", "for" => "email", "class" => "control-label")) ?>
					<?php 
					$data = array('id' => 'email', 'name' => 'email', "type" => "email", "value" => set_value("email", isset($kontakt["email"]) ? $kontakt["email"]->kontakt : "" ), "class" => "form-control");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : "";
					echo form_input($data); ?>
					<?php echo form_error("email"); ?>
				</div>
			</div>
		</div>
		<legend class=""><?php echo $this->getPhrase("Personal/DokumentenUpload", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></legend>
		<div class="row form-row">
			<div class="col-sm-12">
				<div class="form-group">
					<?php echo $this->getPhrase("Personal/PleaseUploadDocuments", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);  ?>
				</div>
			</div>
		</div>
		<hr>
		<div class="row form-row">
			<div class="col-sm-2">
				<?php echo form_label($this->lang->line('person_formDokumentupload_reisepass'), "reisepass", array("name" => "reisepass", "for" => "reisepass", "class" => "control-label")) ?>
			</div>
			<?php
			if(isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->mimetype))
			{
				switch($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->mimetype)
				{
					case "application/pdf":
						$logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						$logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						$logo = "docx.gif";
					default:
						if(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->titel, "docx") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->titel, "doc") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						else
						{
							$logo = false;
							break;
						}
				}
			}
			else
			{
				$logo = "";
			}
			?>
			<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-6">
				<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>">
					<?php
						if ((!isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]])) || ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht === "t"))
						{
							echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
						}
						else
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name."</br>";
							echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
						}
					?>
					<!-- The global progress bar -->
					<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="progress">
						<div class="progress-bar progress-bar-success"></div>
					</div>
				</div>
			<!--<div class="checkbox">
			<label>
				<?php
					$data = array('id' => 'reisepass_nachgereicht', 'name' => 'reisepass_nachgereicht', "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
					(isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
					echo form_checkbox($data);
					echo $this->lang->line('person_formNachgereicht')
				?>
			</label>
			</div>-->
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<div class="form-group <?php echo (form_error("reisepass") != "") ? 'has-error' : '' ?>">
						<div class="upload">
							<?php
								//echo form_input(array('id' => 'reisepass_'.$studiengang->studienplan->studienplan_id, 'name' => 'reisepass', "type" => "file"));
								echo form_error("reisepass");
							?>
						</div>
					</div>
				</div>
				<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('reisepass', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button> -->

				<!-- The fileinput-button span is used to style the file input field as button -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>">
					<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]])) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht == "f") && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id != null)) { ?>
						<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dms_id; ?>, <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>
					<?php
					}
					?>
				</div>
				<div id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Upload_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->nachgereicht == "f")) ? 'display: none;' : ''; ?>">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<span><?php echo $this->lang->line("aufnahme_dateiAuswahl"); ?></span>
						<!-- The file input field used as target for the file upload widget -->
						<input id="<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>" type="file" name="files[]">
					</span>
				</div>
			</div>
		</div>
		<hr>
		<div class="row form-row">
			<div class="col-sm-2">
				<?php echo form_label($this->lang->line('person_formDokumentupload_lebenslauf')."&nbsp;", "lebenslauf", array("name" => "lebenslauf", "for" => "lebenslauf", "class" => "control-label")) ?><span class="fhc-tooltip glyphicon glyphicon-info-sign" aria-hidden="true" title="inklusive Foto"></span>
			</div>
			<?php
			if((isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype !== null))
			{
				switch($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype)
				{
					case "application/pdf":
						$logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						$logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						$logo = "docx.gif";
					default:
						if(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->titel, "docx") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->titel, "doc") !== false)
						{
							$logo = "docx.gif";
							break;
						}
						else
						{
							$logo = false;
							break;
						}
				}
			}
			else
			{
				$logo = "";
			}
			?>
			<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-6">
				
				<div class="form-group" id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>">
					<?php
						if ((!isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]])) || ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht === "t"))
						{
							echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
						}
						else
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name."</br>";
							echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
						}
					?>
					<!-- The global progress bar -->
					<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="progress">
						<div class="progress-bar progress-bar-success"></div>
					</div>
				</div>
			<!--<div class="checkbox">
			<label>
				<?php
					$data = array('id' => 'lebenslauf_nachgereicht', 'name' => 'lebenslauf_nachgereicht', "checked" => (isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
					(isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
					echo form_checkbox($data);
					echo $this->lang->line('person_formNachgereicht')
				?>
			</label>
			</div>-->
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<div class="form-group <?php echo (form_error("lebenslauf") != "") ? 'has-error' : '' ?>">
						<div class="upload">
							<?php
								//echo form_input(array('id' => 'lebenslauf_'.$studiengang->studienplan->studienplan_id, 'name' => 'lebenslauf', "type" => "file"));
								echo form_error("lebenslauf");
							?>
						</div>
					</div>
				</div>
				<!-- <button class="btn btn-primary icon-upload" type="button" onclick="uploadFiles('lebenslauf', <?php echo $studiengang->studienplan->studienplan_id; ?>)">Upload</button> -->

				<!-- The fileinput-button span is used to style the file input field as button -->
				<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>">
					<?php if((isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]])) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht == "f") && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id != null)) { ?>
					<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument(<?php echo $dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dms_id; ?>, <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>
					<?php
					}
					?>
				</div>
				<div id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Upload_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="<?php echo (isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->nachgereicht == "f")) ? 'display: none;' : ''; ?>">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<span><?php echo $this->lang->line("aufnahme_dateiAuswahl"); ?></span>
						<!-- The file input field used as target for the file upload widget -->
						<input id="<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>" type="file" name="files[]">
					</span>
				</div>
			</div>
		</div>
		<hr>
		<div class="row form-row">
			<div class="col-sm-4">
				<div class="form-group">
					<?php 
					$data = array("content"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit");
					(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
					echo form_button($data); ?>
				</div>
			</div>
		</div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function() {

		$(".datepicker").datepicker({
			dateFormat: "dd.mm.yy",
			maxDate: new Date(),
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 10);
				}, 0);
			},
			changeYear: true
		});

		$(".fhc-tooltip").tooltip();

		$('input[type=file]').on('change', prepareUpload);

		$(".zustelladresse").each(function(i,v) {
			if($(v).prop("checked"))
			{
				var id = $(v).attr("studienplan_id");
				$("#zustelladresse_"+id).show();
			}
		});

		$(".zustelladresse").click(function(event) {
			var id = $(event.currentTarget).attr("studienplan_id");
			if($(event.currentTarget).prop("checked"))
			{
				$("#zustelladresse_"+id).show();
			}
			else
			{
				$("#zustelladresse_"+id).hide();
			}
		});

		$("#adresse_nation").on("change", function(event) {
			toggleAdresse();
		});

		$("#zustelladresse_nation").on("change", function(event) {
			toggleZustellAdresse();
		});

		$("#plz").on("change", function(event) {
			var plz = $("#plz").val();
			loadOrtData(plz, $("#ort_dropdown"));
		});

		$("#zustell_plz").on("change", function(event) {
			var plz = $("#zustell_plz").val();
			loadOrtData(plz, $("#zustell_ort_dropdown"));
		});
		
		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/uploadFiles/reisepass"); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
				
				var uploadErrors = [];
				var acceptFileTypes = /^.*\.(jpe?g|docx?|pdf)$/i;
				
				if (typeof data.originalFiles[0]['size'] != 'undefined' && data.originalFiles[0]['size'] > 1024 * 1024 * 4)
				{
					uploadErrors.push('Datei zu groß');
				}
				if (typeof data.originalFiles[0]['name'] != 'undefined' && !acceptFileTypes.test(data.originalFiles[0]['name']))
				{
					uploadErrors.push('Kein zulässiger Dateityp');
				}
				if (uploadErrors.length > 0)
				{
					alert(uploadErrors.join("\n"));
				}
				else
				{
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {
				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>').show();
					var logo = "";
					switch(data.result.mimetype)
					{
						case "application/pdf":
						logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						logo = "docx.gif";
					default:
						if(data.result.bezeichnung.strpos("docx") != -1)
						{
							logo = "docx.gif";
							break;
						}
						else if(data.result.bezeichnung.strpos("doc") != -1)
						{
							logo = "docx.gif";
							break;
						}
						else
						{
							logo = false;
							break;
						}
					}

					$("#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").append('<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["reisepass"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

		// File upload
		$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').fileupload({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/uploadFiles/lebenslauf"); ?>',
			dataType: 'json',
			disableValidation: false,
			add: function(e, data) {
				var uploadErrors = [];
				var acceptFileTypes = /^.*\.(jp?g|doc?|pdf)$/i;

				if (typeof data.originalFiles[0]['size'] != 'undefined' && data.originalFiles[0]['size'] > 1024 * 1024 * 4)
				{
					uploadErrors.push('Datei zu groß');
				}
				if (typeof data.originalFiles[0]['name'] != 'undefined' && !acceptFileTypes.test(data.originalFiles[0]['name']))
				{
					uploadErrors.push('Kein zulässiger Dateityp');
				}
				if (uploadErrors.length > 0)
				{
					alert(uploadErrors.join("\n"));
				}
				else
				{
					data.originalFiles['<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>'] = data.originalFiles[0];
					data.submit();
				}
			},
			done: function (e, data) {
				var msg = "";
				if (data.result.success === true)
				{
					msg = "Upload erfolgreich";
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>FileUpload_<?php echo $studiengang->studienplan->studienplan_id; ?>').parent().hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?>').hide();
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Delete_<?php echo $studiengang->studienplan->studienplan_id; ?>').append(
							'<button type="button" class="btn btn-sm btn-primary" onclick="deleteDocument('+data.result.dms_id+', <?php echo $studiengang->studienplan->studienplan_id; ?>);"><span class="glyphicon glyphicon-trash"></span></button>');
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("disabled", true);
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_nachgereicht_<?php echo $studiengang->studienplan->studienplan_id; ?>').prop("checked", false);
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
			$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>').show();
					var logo = "";
					switch(data.result.mimetype)
					{
						case "application/pdf":
						logo = "pdf.jpg";
						break;
							
					case "image/jpeg":
						logo = "";
						break;
					
					case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
						logo = "docx.gif";
					default:
						if(data.result.bezeichnung.strpos("docx") != -1)
						{
							logo = "docx.gif";
							break;
						}
						else if(data.result.bezeichnung.strpos("doc") != -1)
						{
							logo = "docx.gif";
							break;
						}
						else
						{
							logo = false;
							break;
						}
					}

					$("#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_logo_<?php echo $studiengang->studienplan->studienplan_id; ?>").append('<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'); ?>/'+logo+'"/>');
					msg += "</br>"+data.result.bezeichnung;
				}
				else
				{
					msg = "Fehler beim Upload";
					$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
						'width',
						'0%'
					);
				}
				$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>_hochgeladen_<?php echo $studiengang->studienplan->studienplan_id; ?>').html(msg);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#<?php echo $this->config->config["dokumentTypen"]["lebenslauf"]; ?>Progress_<?php echo $studiengang->studienplan->studienplan_id; ?> .progress-bar').css(
					'width',
					progress + '%'
				);
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');

		toggleAdresse();
		toggleZustellAdresse();
    });

    function toggleAdresse()
    {
		var code = $("#adresse_nation option:selected").val();
		if(code === "A")
		{
			hideElement($("#ort_input"));
			showElement($("#ort_dropdown"));
			var plz = $("#plz").val();
			loadOrtData(plz, $("#ort_dropdown"));
		}
		else
		{
			showElement($("#ort_input"));
			hideElement($("#ort_dropdown"));
		}
    }

    function toggleZustellAdresse()
    {
		var code = $("#zustelladresse_nation option:selected").val();
		if(code === "A")
		{
			hideElement($("#zustell_ort_input"));
			showElement($("#zustell_ort_dropdown"));
			var plz = $("#zustell_plz").val();
			loadOrtData(plz, $("#zustell_ort_dropdown"));
		}
		else
		{
			showElement($("#zustell_ort_input"));
			hideElement($("#zustell_ort_dropdown"));
		}
    }

    function hideElement(ele)
    {
		$(ele).hide();
    }

    function showElement(ele)
    {
		$(ele).show();
    }

    var files;

    function prepareUpload(event)
    {
		files = event.target.files;
    }

    // Catch the form submit and upload the files
    /*function uploadFiles(document_kurzbz, studienplan_id)
    {
		// START A LOADING SPINNER HERE

		// Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value) {
			data.append(document_kurzbz, value);
		});

		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/uploadFiles"); ?>',
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			success: function(data, textStatus, jqXHR) {
				if(data.success === true)
				{
					// Success
					$("#"+document_kurzbz+'_'+studienplan_id).after("<span><?php echo $this->lang->line('person_UploadErfolgreich');?></span>");
					$("#"+document_kurzbz+'_hochgeladen').html("<span><?php echo $this->lang->line('person_formDokumentupload_DokHochgeladen'); ?></span>");
				}
				else
				{
					// Handle errors here
					$("#"+document_kurzbz+'_'+studienplan_id).after("<span><?php echo $this->lang->line('person_UploadError');?></span>");
					console.log('ERRORS: ' + data.error);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
    }*/

    function loadOrtData(plz, ele)
    {
		$.ajax({
			method: "GET",
			url: "<?php echo base_url($this->config->config["index_page"]."/Bewerbung/ort"); ?>/"+plz,
			dataType: "json"
		}).done(function(data) {
			if(data.error === 0)
			{
				var select = $(ele).find("select");
				$(select).empty();
				$.each(data.retval, function(i, v) {
					if($(select).attr("name") === "ort_dd")
					{
						if(v.gemeinde_id === '<?php echo isset($ort_dd) ? $ort_dd : ""; ?>')
						{
							$(ele).find("select").append("<option value='"+v.gemeinde_id+"' selected>"+v.ortschaftsname+"</option>");
						}
						else
						{
							$(ele).find("select").append("<option value='"+v.gemeinde_id+"'>"+v.ortschaftsname+"</option>");
						}
					}
					else
					{
						if(v.gemeinde_id === '<?php echo isset($zustell_ort_dd) ? $zustell_ort_dd : ""; ?>')
						{
							$(ele).find("select").append("<option value='"+v.gemeinde_id+"' selected>"+v.ortschaftsname+"</option>");
						}
						else
						{
							$(ele).find("select").append("<option value='"+v.gemeinde_id+"'>"+v.ortschaftsname+"</option>");
						}
					}
				});
			}
		});
    }
</script>
