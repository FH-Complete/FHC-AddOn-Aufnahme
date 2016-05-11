
<div role="tabpanel" class="tab-pane" id="daten">
    <legend><?php echo $this->lang->line("aufnahme/angabenZurPerson"); ?></legend>
    <?php echo form_open("Person", array("id" => "PersonForm", "name" => "PersonForm")); ?>
    <div class="row">
        <div class="col-sm-2">
            <div class="form-group <?php echo (form_error("anrede") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formAnrede'), "anrede", array("name" => "anrede", "for" => "anrede", "class" => "control-label")) ?>
                <?php echo form_dropdown("anrede", array("Herr" => "Herr", "Frau" => "Frau"), "Herr", array('id' => 'anrede', 'name' => 'anrede', "value" => set_value("anrede"), "class" => "form-control")); ?>
                <?php echo form_error("anrede"); ?>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group <?php echo (form_error("akadgrad") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formAkadgrad'), "akadgrad", array("name" => "akadgrad", "for" => "akadgrad", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'akadgrad', 'name' => 'akadgrad', 'maxlength' => 64, "type" => "text", "value" => set_value("akadgrad"), "class" => "form-control")); ?>
                <?php echo form_error("akadgrad"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("vorname") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/vorname'), "vorname", array("name" => "vorname", "for" => "vorname", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'vorname', 'name' => 'vorname', 'maxlength' => 32, "type" => "text", "value" => set_value("vorname"), "class" => "form-control")); ?>
                <?php echo form_error("vorname"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("nachname") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/nachname'), "nachname", array("name" => "nachname", "for" => "nachname", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'nachname', 'name' => 'nachname', 'maxlength' => 64, "type" => "text", "value" => set_value("nachname"), "class" => "form-control")); ?>
                <?php echo form_error("nachname"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("geburtsdatum") != "") ? 'has-error' : '' ?>">
                <?php
                //TODO generate data
                $days = array("1" => "01", "2" => "02");
                $months = array("1" => "01", "2" => "02");
                $years = array("1989" => "1989", "1990" => "1990", "1991" => "1991", "1992" => "1992");
                ?>

                <?php echo form_label($this->lang->line('aufnahme/geburtsdatum'), "geburtsdatum", array("name" => "geburtsdatum", "for" => "geburtsdatum", "class" => "control-label")) ?>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3 nopadding">
                            <?php echo form_dropdown("geburtsdatum_tag", $days, "01", array('id' => 'geburtsdatum_tag', 'name' => 'geburtsdatum_tag', "value" => set_value("geburtsdatum_tag"), "class" => "form-control")); ?>
                        </div>
                        <div class="col-sm-3 nopadding">
                            <?php echo form_dropdown("geburtsdatum_monat", $months, "01", array('id' => 'geburtsdatum_monat', 'name' => 'geburtsdatum_monat', "value" => set_value("geburtsdatum_monat"), "class" => "form-control")); ?>
                        </div>
                        <div class="col-sm-6 nopadding">
                            <?php echo form_dropdown("geburtsdatum_jahr", $years, null, array('id' => 'geburtsdatum_jahr', 'name' => 'geburtsdatum_jahr', "value" => set_value("geburtsdatum_jahr"), "class" => "form-control")); ?>
                        </div>
                    </div>
                </div>
                <?php echo form_error("geburtsdatum"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("geburtsort") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/geburtsort'), "geburtsort", array("name" => "geburtsort", "for" => "geburtsort", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'geburtsort', 'name' => 'geburtsort', "type" => "text", "value" => set_value("geburtsort"), "class" => "form-control")); ?>
                <?php echo form_error("geburtsort"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("staatsbuergerschaft") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/staatsbuergerschaft'), "staatsbuergerschaft", array("name" => "staatsbuergerschaft", "for" => "staatsbuergerschaft", "class" => "control-label")) ?>
                <?php echo form_dropdown("staatsbuergerschaft", $nationen, null, array('id' => 'staatsbuergerschaft', 'name' => 'staatsbuergerschaft', "value" => set_value("staatsbuergerschaft"), "class" => "form-control")); ?>
                <?php echo form_error("staatsbuergerschaft"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("nation") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formGeburtsnation'), "nation", array("name" => "nation", "for" => "nation", "class" => "control-label")) ?>
                <?php echo form_dropdown("nation", $nationen, null, array('id' => 'nation', 'name' => 'nation', "value" => set_value("nation"), "class" => "form-control")); ?>
                <?php echo form_error("nation"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("svn") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formSvn'), "svn", array("name" => "svn", "for" => "svn", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'svn', 'name' => 'svn', "type" => "text", "value" => set_value("svn"), "class" => "form-control")); ?>
                <?php echo form_error("svn"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("geschlecht") != "") ? 'has-error' : '' ?>">
                <fieldset><?php echo $this->lang->line('aufnahme/geschlecht'); ?></fieldset>
                <?php echo form_radio(array("id" => "geschlecht", "name" => "geschlecht"), null, null); ?>
                <span><?php echo $this->lang->line("aufnahme/formMaennlich"); ?></span>
                <?php echo form_radio(array("id" => "geschlecht", "name" => "geschlecht"), null, null); ?>
                <span><?php echo $this->lang->line("aufnahme/formWeiblich"); ?></span>
                <?php echo form_error("geschlecht"); ?>
            </div>
        </div>
    </div>
    <legend class=""><?php echo $this->lang->line("aufnahme/adresse"); ?></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group <?php echo (form_error("heimatadresse") != "") ? 'has-error' : '' ?>">
                <fieldset><?php echo $this->lang->line('aufnahme/heimatadresse'); ?></fieldset>
                <?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse"), null, null); ?>
                <span><?php echo sprintf($this->lang->line("aufnahme/formHeimatadresse"), "Inland"); ?></span>
                <?php echo form_radio(array("id" => "heimatadresse", "name" => "heimatadresse"), null, null); ?>
                <span><?php echo sprintf($this->lang->line("aufnahme/formHeimatadresse"), "Ausland"); ?></span>
                <?php echo form_error("heimatadresse"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("strasse") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/strasse'), "strasse", array("name" => "strasse", "for" => "strasse", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'strasse', 'name' => 'strasse', "type" => "text", "value" => set_value("strasse"), "class" => "form-control")); ?>
                <?php echo form_error("strasse"); ?>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group <?php echo (form_error("plz") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formPlz'), "plz", array("name" => "plz", "for" => "plz", "class" => "control-label")) ?>
                <?php echo form_dropdown("plz", $plz, null, array('id' => 'plz', 'name' => 'plz', "value" => set_value("plz"), "class" => "form-control")); ?>
                <?php echo form_error("plz"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("bundesland") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/formBundesland'), "bundesland", array("name" => "bundesland", "for" => "bundesland", "class" => "control-label")) ?>
                <?php echo form_dropdown("bundesland", $bundeslaender, null, array('id' => 'bundesland', 'name' => 'bundesland', "value" => set_value("bundesland"), "class" => "form-control")); ?>
                <?php echo form_error("bundesland"); ?>
            </div>
        </div>
    </div>
    <legend class=""><?php echo $this->lang->line("aufnahme/formKontakt"); ?></legend>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("telefon") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/telefon'), "telefon", array("name" => "telefon", "for" => "telefon", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'telefon', 'name' => 'telefon', "type" => "text", "value" => set_value("telefon"), "class" => "form-control")); ?>
                <?php echo form_error("telefon"); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("fax") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/fax'), "fax", array("name" => "fax", "for" => "fax", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'fax', 'name' => 'fax', "type" => "text", "value" => set_value("fax"), "class" => "form-control")); ?>
                <?php echo form_error("fax"); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group <?php echo (form_error("email") != "") ? 'has-error' : '' ?>">
                <?php echo form_label($this->lang->line('aufnahme/emailAdresse'), "email", array("name" => "email", "for" => "email", "class" => "control-label")) ?>
                <?php echo form_input(array('id' => 'email', 'name' => 'email', "type" => "text", "value" => set_value("email"), "class" => "form-control")); ?>
                <?php echo form_error("email"); ?>
            </div>
        </div>
    </div>
    <legend class=""><?php echo $this->lang->line("aufnahme/formDokumentupload"); ?></legend>
    <div class="row">

    </div>
</div>
<?php echo form_close(); ?>
</div>
