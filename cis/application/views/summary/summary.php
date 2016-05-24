<div role="tabpanel" class="tab-pane" id="summary">
    <h1><?php echo $this->lang->line("summary_header"); ?></h1>
    <fieldset><?php echo $this->lang->line("summary_einleitung").'.'; ?></fieldset>

    <legend><?php echo $this->lang->line("summary_persoenlicheDaten"); ?></legend>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_anrede"); ?></div>
            <div class="col-sm-6"><?php echo ($person->anrede != null) ? $person->anrede : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_adresse"); ?></div>
            <div class="col-sm-6"><?php echo ($adresse->strasse != null) ? $adresse->strasse : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_titelpre"); ?></div>
            <div class="col-sm-6"><?php echo ($person->titelpre != null) ? $person->titelpre : ""; ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_plzOrt"); ?></div>
            <div class="col-sm-6"><?php echo (($adresse->plz != null) && ($adresse->ort != null))? $adresse->plz." ".$adresse->ort : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_vorname"); ?></div>
            <div class="col-sm-6"><?php echo ($person->vorname != null) ? $person->vorname : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_bundesland"); ?></div>
            <div class="col-sm-6"><?php echo ($person->bundesland_code != null) ? $person->bundesland_bezeichnung : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_nachname"); ?></div>
            <div class="col-sm-6"><?php echo ($person->nachname != null) ? $person->nachname : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_telefon"); ?></div>
            <div class="col-sm-6"><?php echo (isset($kontakt["telefon"])) ? $kontakt["telefon"]->kontakt : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsdatum"); ?></div>
            <div class="col-sm-6"><?php echo ($person->gebdatum != null) ? $person->gebdatum : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_fax"); ?></div>
            <div class="col-sm-6"><?php echo (isset($kontakt["fax"])) ? $kontakt["fax"]->kontakt : ""?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsort"); ?></div>
            <div class="col-sm-6"><?php echo ($person->gebort != null) ? $person->gebort : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_mail"); ?></div>
            <div class="col-sm-6"><?php echo (isset($kontakt["email"])) ? $kontakt["email"]->kontakt : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsnation"); ?></div>
            <div class="col-sm-6"><?php echo ($person->geburtsnation != null) ? $person->geburtsnation : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6"></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_staatsbuergerschaft"); ?></div>
            <div class="col-sm-6"><?php echo ($person->staatsbuergerschaft != null) ? $person->staatsbuergerschaft : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6"></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_svnr"); ?></div>
            <div class="col-sm-6"><?php echo ($person->svnr != null) ? $person->svnr : $this->lang->line("summary_unvollstaendig"); ?></div>
        </div>
        <div class="col-sm-6"></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-6"><?php echo $this->lang->line("summary_geschlecht"); ?></div>
            <div class="col-sm-6">
                <?php
                if($person->geschlecht != null)
                {
                    switch($person->geschlecht)
                    {
                        case "m":
                            echo $this->lang->line("summary_geschlecht_m");
                            break;
                        case "m":
                            echo $this->lang->line("summary_geschlecht_m");
                            break;
                        default:
                            echo $this->lang->line("summary_unvollstaendig");
                            break;
                    }
                }
                else
                {
                    echo $this->lang->line("summary_unvollstaendig");
                }
                
                
                ?>
            </div>
        </div>
        <div class="col-sm-6"></div>
    </div>
    <legend><?php echo $this->lang->line("summary_requirements_header"); ?></legend>
    
    <legend><?php echo $this->lang->line("summary_specific_requirements_header"); ?></legend>
    
    <legend><?php echo $this->lang->line("summary_motivation_header"); ?></legend>
    
</div>
