<div role="tabpanel" class="tab-pane" id="daten">
    <legend><?php echo $this->getPhrase("Personal/Information", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></legend>
    <?php echo form_open_multipart("Bewerbung", array("id" => "PersonForm", "name" => "PersonForm")); ?>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group <?php echo (form_error("anrede") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formAnrede'), "anrede", array("name" => "anrede", "for" => "anrede", "class" => "control-label")) ?>
                <?php echo form_dropdown("anrede", array("Herr" => "Herr", "Frau" => "Frau"), isset($person->anrede) ? $person->anrede : "Herr", array('id' => 'anrede', 'name' => 'anrede', "class" => "form-control")); ?>
                <?php echo form_error("anrede"); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group <?php echo (form_error("titelpre") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formPrenomen'), "titelpre", array("name" => "titelpre", "for" => "titelpre", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'titelpre', 'name' => 'titelpre', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpre", isset($person->titelpre) ? $person->titelpre : ""), "class" => "form-control")); ?>
                <?php echo form_error("titelpre"); ?>
            </div>
        </div>
	<div class="col-sm-3">
            <div class="form-group <?php echo (form_error("titelpost") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formPostnomen'), "titelpost", array("name" => "titelpost", "for" => "titelpost", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'titelpost', 'name' => 'titelpost', 'maxlength' => 64, "type" => "text", "value" => set_value("titelpost", isset($person->titelpost) ? $person->titelpost : ""), "class" => "form-control")); ?>
                <?php echo form_error("titelpost"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("vorname") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_vorname'), "vorname", array("name" => "vorname", "for" => "vorname", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'vorname', 'name' => 'vorname', 'maxlength' => 32, "type" => "text", "value" => set_value("vorname", isset($person->vorname) ? $person->vorname : ""), "class" => "form-control")); ?>
                <?php echo form_error("vorname"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("nachname") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_nachname'), "nachname", array("name" => "nachname", "for" => "nachname", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'nachname', 'name' => 'nachname', 'maxlength' => 64, "type" => "text", "value" => set_value("nachname", isset($person->nachname) ? $person->nachname : ""), "class" => "form-control")); ?>
                <?php echo form_error("nachname"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("gebdatum") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_geburtsdatum'), "gebdatum", array("name" => "gebdatum", "for" => "gebdatum", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'gebdatum', 'name' => 'gebdatum', 'maxlength' => 64, "type" => "date", "value" => set_value("gebdatum", isset($person->gebdatum) ? $person->gebdatum : ""), "class" => "form-control datepicker")); ?>
                <?php echo form_error("gebdatum"); ?>
            </div>
            <!--<div class="form-group <?php echo (form_error("geburtsdatum") != "") ? 'has-error' : '' ?>">
                <?php
                //TODO generate data
                $days = array("1" => "01", "2" => "02");
                $months = array("1" => "01", "2" => "02");
                $years = array("1989" => "1989", "1990" => "1990", "1991" => "1991", "1992" => "1992");
                ?>

                <?php echo form_label($this->lang->line('person_geburtsdatum'), "geburtsdatum", array("name" => "geburtsdatum", "for" => "geburtsdatum", "class" => "control-label")) ?>
                <div class="col-sm-12">
                    <div class="row">
                        <?php /*
                        var_dump(explode("-", $person->gebdatum));
                        
                        $geburtsdatum = explode("-", $person->gebdatum);
                        ?>
                        <div class="col-sm-3 nopadding">
                            <?php echo form_dropdown("geburtsdatum_tag", $days, isset($geburtsdatum[2]) ? $geburtsdatum[2] : "01", array('id' => 'geburtsdatum_tag', 'name' => 'geburtsdatum_tag', "class" => "form-control")); ?>
                        </div>
                        <div class="col-sm-3 nopadding">
                            <?php echo form_dropdown("geburtsdatum_monat", $months, "01", array('id' => 'geburtsdatum_monat', 'name' => 'geburtsdatum_monat', "class" => "form-control")); ?>
                        </div>
                        <div class="col-sm-6 nopadding">
                            <?php echo form_dropdown("geburtsdatum_jahr", $years, (isset($geburtsdatum[0]) ? $geburtsdatum[0] : "01"), array('id' => 'geburtsdatum_jahr', 'name' => 'geburtsdatum_jahr', "class" => "form-control")); ?>
                        </div>
                        <?php */?>
                    </div>
                </div>
                <?php echo form_error("geburtsdatum"); ?>
            </div>-->
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("geburtsort") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_geburtsort'), "geburtsort", array("name" => "geburtsort", "for" => "geburtsort", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'geburtsort', 'name' => 'geburtsort', "type" => "text", "value" => set_value("geburtsort", (isset($person->gebort) ? $person->gebort : "")), "class" => "form-control")); ?>
                <?php echo form_error("geburtsort"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("staatsbuergerschaft") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_staatsbuergerschaft'), "staatsbuergerschaft", array("name" => "staatsbuergerschaft", "for" => "staatsbuergerschaft", "class" => "control-label")) ?>
                <?php echo form_dropdown("staatsbuergerschaft", $nationen, (isset($person->staatsbuergerschaft) ? $person->staatsbuergerschaft : "A"), array('id' => 'staatsbuergerschaft', 'name' => 'staatsbuergerschaft', "class" => "form-control")); ?>
                <?php echo form_error("staatsbuergerschaft"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("nation") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formGeburtsnation'), "nation", array("name" => "nation", "for" => "nation", "class" => "control-label")) ?>
                <?php echo form_dropdown("nation", $nationen, (isset($person->geburtsnation) ? $person->geburtsnation : "A"), array('id' => 'nation', 'name' => 'nation', "value" => set_value("nation"), "class" => "form-control")); ?>
                <?php echo form_error("nation"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("svnr") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formSvn'), "svnr", array("name" => "svnr", "for" => "svnr", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'svnr', 'name' => 'svnr', "type" => "text", "value" => set_value("svnr", (isset($person->svnr) ? $person->svnr : "")), "class" => "form-control")); ?>
                <?php echo form_error("svnr"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("geschlecht") != "") ? 'has-error' : '' ?>">
                <fieldset><?php echo $this->lang->line('person_geschlecht'); ?></fieldset>
                <?php echo form_radio(array("id" => "geschlecht_m", "name" => "geschlecht"), "m" , (isset($person->geschlecht) && $person->geschlecht=="m") ? true : false); ?>
                <span><?php echo $this->lang->line("person_formMaennlich"); ?></span>
                <?php echo form_radio(array("id" => "geschlecht_f", "name" => "geschlecht"), "f", (isset($person->geschlecht) && $person->geschlecht=="f") ? true : false); ?>
                <span><?php echo $this->lang->line("person_formWeiblich"); ?></span>
                <?php echo form_error("geschlecht"); ?>
            </div>
        </div>
    </div>
    <legend class=""><?php echo $this->lang->line("person_adresse"); ?></legend>
    <!--<div class="row">
        <div class="col-sm-12">
            <div class="form-group <?php echo (form_error("heimatadresse") != "") ? 'has-error' : '' ?>">
                <fieldset><?php echo $this->lang->line('person_heimatadresse'); ?></fieldset>
                <?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse", "checked"=>"checked"), null, null); ?>
                <span><?php echo sprintf($this->lang->line("person_formHeimatadresse"), "Inland"); ?></span>
                <?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse"), null, null); ?>
                <span><?php echo sprintf($this->lang->line("person_formHeimatadresse"), "Ausland"); ?></span>
                <?php echo form_error("heimatadresse"); ?>
            </div>
        </div>
    </div>-->
    <div class="row">
	<div class="col-sm-6">
            <div class="form-group <?php echo (form_error("adresse_nation") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formAdresseNation'), "adresse_nation", array("name" => "adresse_nation", "for" => "adresse_nation", "class" => "control-label")) ?>
                <?php echo form_dropdown("adresse_nation", $nationen, (isset($adresse->nation) ? $adresse->nation : "A"), array('id' => 'adresse_nation', 'name' => 'adresse_nation', "value" => set_value("adresse_nation"), "class" => "form-control")); ?>
                <?php echo form_error("adresse_nation"); ?>
            </div>
        </div>
    </div>
    <div class="row">
	<div class="col-sm-6">
            <div class="form-group <?php echo (form_error("plzOrt") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formPlzOrt'), "plzOrt", array("name" => "plzOrt", "for" => "plzOrt", "class" => "control-label")) ?>
                <?php echo form_dropdown("plzOrt", $plz, (isset($gemeinde_id) ? $gemeinde_id : null), array('id' => 'plzOrt', 'name' => 'plzOrt', "value" => set_value("plzOrt"), "class" => "form-control")); ?>
                <?php echo form_error("plzOrt"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("strasse") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_strasse'), "strasse", array("name" => "strasse", "for" => "strasse", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'strasse', 'name' => 'strasse', "type" => "text", "value" => set_value("strasse", (isset($adresse->strasse) ? $adresse->strasse : NULL)), "class" => "form-control")); ?>
                <?php echo form_error("strasse"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group <?php echo (form_error("plz") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formPlz'), "plz", array("name" => "plz", "for" => "plz", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'plz', 'name' => 'plz', "type" => "text", "value" => set_value("plz", (isset($adresse->plz) ? $adresse->plz : NULL)), "class" => "form-control")); ?>
                <?php echo form_error("plz"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("ort") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_formOrt'), "ort", array("name" => "ort", "for" => "ort", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'ort', 'name' => 'ort', "type" => "text", "value" => set_value("ort", (isset($adresse->ort) ? $adresse->ort : NULL)), "class" => "form-control")); ?>
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
    <div class="row">
	<div class="col-sm-12">
	    <div class="form-group <?php echo (form_error("zustelladresse") != "") ? 'has-error' : '' ?>">
		<div class="checkbox">
		    <label>
			<?php echo form_checkbox(array('id' => 'zustelladresse', 'name' => 'zustelladresse', "checked" => isset($zustell_adresse) ? TRUE : FALSE, "class"=>"zustelladresse", "studienplan_id"=>$studiengang->studienplan->studienplan_id));
			    echo $this->getPhrase("Personal/DifferentAddress", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
			?>			
		    </label>
		</div>
		<?php echo form_error("zustelladresse"); ?>
	    </div>
	</div>
    </div>
    <div id="zustelladresse_<?php echo $studiengang->studienplan->studienplan_id; ?>" style="display: none;">
	<legend class=""><?php echo $this->lang->line("person_formZustelladresse"); ?></legend>
	<div class="row">
	    <div class="col-sm-6">
		<div class="form-group <?php echo (form_error("zustelladresse_nation") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('person_formAdresseNation'), "zustelladresse_nation", array("name" => "zustelladresse_nation", "for" => "zustelladresse_nation", "class" => "control-label")) ?>
		    <?php echo form_dropdown("zustelladresse_nation", $nationen, (isset($zustell_adresse->nation) ? $zustell_adresse->nation : "A"), array('id' => 'zustelladresse_nation', 'name' => 'zustelladresse_nation', "value" => set_value("zustelladresse_nation"), "class" => "form-control")); ?>
		    <?php echo form_error("zustelladresse_nation"); ?>
		</div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-6">
		<div class="form-group <?php echo (form_error("zustell_plzOrt") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('person_formPlzOrt'), "zustell_plzOrt", array("name" => "zustell_plzOrt", "for" => "zustell_plzOrt", "class" => "control-label")) ?>
		    <?php echo form_dropdown("zustell_plzOrt", $plz, (isset($zustell_gemeinde_id) ? $zustell_gemeinde_id : null), array('id' => 'zustell_plzOrt', 'name' => 'zustell_plzOrt', "value" => set_value("zustell_plzOrt"), "class" => "form-control")); ?>
		    <?php echo form_error("zustell_plzOrt"); ?>
		</div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-8">
		<div class="form-group <?php echo (form_error("zustell_strasse") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('person_strasse'), "zustell_strasse", array("name" => "zustell_strasse", "for" => "zustell_strasse", "class" => "control-label")) ?>
		    <?php echo form_input(array('id' => 'zustell_strasse', 'name' => 'zustell_strasse', "type" => "text", "value" => set_value("zustell_strasse", (isset($zustell_adresse->strasse) ? $zustell_adresse->strasse : NULL)), "class" => "form-control")); ?>
		    <?php echo form_error("zustell_strasse"); ?>
		</div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
		<div class="form-group <?php echo (form_error("zustell_plz") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('person_formPlz'), "zustell_plz", array("name" => "zustell_plz", "for" => "zustell_plz", "class" => "control-label")) ?>
		    <?php echo form_input(array('id' => 'zustell_plz', 'name' => 'zustell_plz', "type" => "text", "value" => set_value("zustell_plz", (isset($zustell_adresse->plz) ? $zustell_adresse->plz : NULL)), "class" => "form-control")); ?>
		    <?php echo form_error("zustell_plz"); ?>
		</div>
	    </div>
	    <div class="col-sm-6">
		<div class="form-group <?php echo (form_error("zustell_ort") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('person_formOrt'), "zustell_ort", array("name" => "zustell_ort", "for" => "zustell_ort", "class" => "control-label")) ?>
		    <?php echo form_input(array('id' => 'zustell_ort', 'name' => 'zustell_ort', "type" => "text", "value" => set_value("zustell_ort", (isset($zustell_adresse->ort) ? $zustell_adresse->ort : NULL)), "class" => "form-control")); ?>
		    <?php echo form_error("zustell_ort"); ?>
		</div>
	    </div>
	</div>
    </div>
    <legend class=""><?php echo $this->lang->line("person_formKontakt"); ?></legend>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("telefon") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_telefon'), "telefon", array("name" => "telefon", "for" => "telefon", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'telefon', 'name' => 'telefon', "type" => "text", "value" => set_value("telefon", isset($kontakt["telefon"]) ? $kontakt["telefon"]->kontakt : "" ), "class" => "form-control")); ?>
                <?php echo form_error("telefon"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("fax") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_fax'), "fax", array("name" => "fax", "for" => "fax", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'fax', 'name' => 'fax', "type" => "text", "value" => set_value("fax", isset($kontakt["fax"]) ? $kontakt["fax"]->kontakt : ""), "class" => "form-control")); ?>
                <?php echo form_error("fax"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('person_emailAdresse'), "email", array("name" => "email", "for" => "email", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'email', 'name' => 'email', "type" => "email", "value" => set_value("email", isset($kontakt["email"]) ? $kontakt["email"]->kontakt : "" ), "class" => "form-control")); ?>
                <?php echo form_error("email"); ?>
            </div>
        </div>
    </div>
    <legend class=""><?php echo $this->lang->line("person_formDokumentupload"); ?></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $this->getPhrase("Personal/PleaseUploadDocuments", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);  ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <?php echo form_label($this->lang->line('person_formDokumentupload_reisepass'), "reisepass", array("name" => "reisepass", "for" => "reisepass", "class" => "control-label")) ?>
            <div class="form-group">
                <?php
		if((!isset($dokumente["pass"])) || ($dokumente["pass"]->nachgereicht === "t")) {
                    echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
                 }
		 else
		 {
		     echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
		 }
                 ?>
            </div>
	    <!--<div class="checkbox">
		<label>
		    <?php
		    $data = array('id' => 'reisepass_nachgereicht', 'name' => 'reisepass_nachgereicht', "checked" => (isset($dokumente["pass"]) && ($dokumente["pass"]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
		    (isset($dokumente["pass"]) && ($dokumente["pass"]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
		    
		    echo form_checkbox($data);
			echo $this->lang->line('person_formNachgereicht')
		    ?>			
		</label>
	    </div>-->
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <div class="form-group <?php echo (form_error("reisepass") != "") ? 'has-error' : '' ?>">
		    <div class="upload">
			<?php echo form_input(array('id' => 'reisepass', 'name' => 'reisepass', "type" => "file")); ?>
			<?php echo form_error("reisepass"); ?>
		    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <?php echo form_label($this->lang->line('person_formDokumentupload_lebenslauf'), "lebenslauf", array("name" => "lebenslauf", "for" => "lebenslauf", "class" => "control-label")) ?>
            <div class="form-group">
                <?php
		if((!isset($dokumente["Lebenslf"])) || ($dokumente["Lebenslf"]->nachgereicht === "t")) {
                    echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen');
                 }
		 else
		 {
		     echo $this->lang->line('person_formDokumentupload_DokHochgeladen');
		 }
                 ?>
            </div>
	    <!--<div class="checkbox">
		<label>
		    <?php
		    $data = array('id' => 'lebenslauf_nachgereicht', 'name' => 'lebenslauf_nachgereicht', "checked" => (isset($dokumente["Lebenslf"]) && ($dokumente["Lebenslf"]->nachgereicht === "t")) ? TRUE : FALSE, "studienplan_id"=>$studiengang->studienplan->studienplan_id);
		    (isset($dokumente["Lebenslf"]) && ($dokumente["Lebenslf"]->dms_id !== null)) ? $data["disabled"] = "disabled" : false;
		    
		    echo form_checkbox($data);
			echo $this->lang->line('person_formNachgereicht')
		    ?>			
		</label>
	    </div>-->
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <div class="form-group <?php echo (form_error("lebenslauf") != "") ? 'has-error' : '' ?>">
		    <div class="upload">
			<?php echo form_input(array('id' => 'lebenslauf', 'name' => 'lebenslauf', "type" => "file")); ?>
			<?php echo form_error("lebenslauf"); ?>
		    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
		<?php echo form_button(array("content"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
	    </div>
	</div>
    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
	$(".datepicker").datepicker({
	    dateFormat: "dd.mm.yy",
	    maxDate: new Date()
	});
    });
</script>
