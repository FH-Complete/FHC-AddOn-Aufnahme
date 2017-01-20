<?php
/**
 * ./cis/application/views/summary/summary_personal.php
 *
 * @package default
 */


?>
<fieldset><?php echo $this->getPhrase("Overview/CompleteAndCheckData", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?></fieldset>

<legend><?php echo $this->lang->line("summary_persoenlicheDaten"); ?></legend>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_anrede"); ?></div>
        <div class="col-sm-6 <?php echo ($person->anrede != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->anrede != null) ? $person->anrede : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_adresse"); ?></div>
        <div class="col-sm-6 <?php echo (isset($adresse->strasse) && ($adresse->strasse != null)) ? "" : "incomplete"; ?>">
	    <?php echo (isset($adresse->strasse) && ($adresse->strasse != null)) ? $adresse->strasse : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_titelpre"); ?></div>
        <div class="col-sm-6"><?php echo ($person->titelpre != null) ? $person->titelpre : ""; ?></div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_plzOrt"); ?></div>
        <div class="col-sm-6 <?php echo ((isset($adresse->strasse) && ($adresse->plz != null)) && (isset($adresse->strasse) && ($adresse->ort != null)))? "" : "incomplete"; ?>">
	    <?php echo ((isset($adresse->strasse) && ($adresse->plz != null)) && (isset($adresse->strasse) && ($adresse->ort != null)))? $adresse->plz." ".$adresse->ort : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_vorname"); ?></div>
        <div class="col-sm-6 <?php echo ($person->vorname != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->vorname != null) ? $person->vorname : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <?php if (isset($adresse) && $adresse->nation == "A") { ?>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_bundesland"); ?></div>
        <div class="col-sm-6 <?php echo ($person->bundesland_code != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->bundesland_code != null) ? $person->bundesland_bezeichnung : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_nachname"); ?></div>
        <div class="col-sm-6 <?php echo ($person->nachname != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->nachname != null) ? $person->nachname : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_telefon"); ?></div>
        <div class="col-sm-6 <?php echo (isset($kontakt["telefon"])) ? "" : "incomplete"; ?>">
	    <?php echo (isset($kontakt["telefon"])) ? $kontakt["telefon"]->kontakt : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsdatum"); ?></div>
        <div class="col-sm-6 <?php echo ($person->gebdatum != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->gebdatum != null) ? $person->gebdatum : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_fax"); ?></div>
        <div class="col-sm-6"><?php echo (isset($kontakt["fax"])) ? $kontakt["fax"]->kontakt : ""?></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsort"); ?></div>
        <div class="col-sm-6 <?php echo ($person->gebort != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->gebort != null) ? $person->gebort : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_mail"); ?></div>
        <div class="col-sm-6 <?php echo (isset($kontakt["email"])) ? "" : "incomplete"; ?>">
	    <?php echo (isset($kontakt["email"])) ? $kontakt["email"]->kontakt : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_geburtsnation"); ?></div>
        <div class="col-sm-6 <?php echo ($person->geburtsnation != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->geburtsnation != null) ? $person->geburtsnation_text : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6"></div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_staatsbuergerschaft"); ?></div>
        <div class="col-sm-6 <?php echo ($person->staatsbuergerschaft != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->staatsbuergerschaft != null) ? $person->staatsbuergerschaft : $this->lang->line("summary_unvollstaendig"); ?>
	</div>
    </div>
    <div class="col-sm-6"></div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_svnr"); ?></div>
        <div class="col-sm-6 <?php echo ($person->svnr != null) ? "" : "incomplete"; ?>">
	    <?php echo ($person->svnr != null) ? mb_substr($person->svnr, 0, 10) : ((($person->geburtsnation != null) && ($person->geburtsnation != "A")) ? "" : $this->lang->line("summary_unvollstaendig")); ?>
	</div>
    </div>
    <div class="col-sm-6"></div>
</div>
<!--<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6"><?php echo $this->lang->line("summary_geschlecht"); ?></div>
        <div class="col-sm-6 <?php echo ($person->geschlecht != null) ? "" : "incomplete"; ?>">
            <?php
//				if ($person->geschlecht != null) {
//					switch ($person->geschlecht) {
//					case "m":
//						echo $this->lang->line("summary_geschlecht_m");
//						break;
//					case "m":
//						echo $this->lang->line("summary_geschlecht_m");
//						break;
//					default:
//						echo $this->lang->line("summary_unvollstaendig");
//						break;
//					}
//				}
//				else {
//					echo $this->lang->line("summary_unvollstaendig");
//				}
				?>
        </div>
    </div>
    <div class="col-sm-6"></div>
</div>-->

<div class="personal_documents">
	<hr>
    <div class="row">
		<div class="col-sm-12">
			<div class="col-sm-4">
				<?php echo $personalDocuments[$this->config->config["dokumentTypen"]["reisepass"]]->bezeichnung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1]; ?>
			</div>
			<?php
			if((isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->mimetype !== null))
			{
				if(isset($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument))
				{
					if(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".docx") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".doc") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".pdf") !== false)
					{
						$logo = "document-pdf.svg";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name, ".jpg") !== false)
					{
						$logo = "document-picture.svg";
					}
					else
					{
						$logo = false;
					}
				}
				else
				{
					$logo = false;
				}
			}
			else
			{
				$logo = "";
			}
			?>
			<div class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-6 <?php echo (!isset($dokumente[$this->config->item('dokumentTypen')["reisepass"]])) ? "incomplete" : ""; ?>">
				<div class="form-group">
					<?php if (!isset($dokumente[$this->config->item('dokumentTypen')["reisepass"]])){
								echo $this->lang->line('summary_unvollstaendig');
							}
							else
							{
								echo $dokumente[$this->config->config["dokumentTypen"]["reisepass"]]->dokument->name;
							}
							?>
				</div>
			</div>
		</div>
    </div>
	<hr>
    <div class="row">
		<div class="col-sm-12">
			<div class="col-sm-4">
				<?php echo $personalDocuments[$this->config->config["dokumentTypen"]["lebenslauf"]]->bezeichnung_mehrsprachig[$this->session->{'Sprache.getSprache'}->retval->index-1]; ?>
			</div>
			<?php
			if((isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype)) && ($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->mimetype !== null))
			{
				if(isset($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument))
				{
					if(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".docx") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".doc") !== false)
					{
						$logo = "docx.gif";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".pdf") !== false)
					{
						$logo = "document-pdf.svg";
					}
					elseif(strpos($dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name, ".jpg") !== false)
					{
						$logo = "document-picture.svg";
					}
					else
					{
						$logo = false;
					}
				}
				else
				{
					$logo = false;
				}
			}
			else
			{
				$logo = "";
			}
			?>
			<div class="col-sm-1">
				<?php 
				if(isset($logo) && ($logo != false))
				{
				?>
				<img class="document_logo" width="30" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/'.$logo); ?>"/>
				<?php
				}
				?>
			</div>
			<div class="col-sm-6 <?php echo (!isset($dokumente[$this->config->item('dokumentTypen')["lebenslauf"]])) ? "incomplete" : ""; ?>">
				<div class="form-group">
					<?php if(!isset($dokumente[$this->config->item('dokumentTypen')["lebenslauf"]])) {
							echo $this->lang->line('summary_unvollstaendig');
						}
						else
						{
							echo $dokumente[$this->config->config["dokumentTypen"]["lebenslauf"]]->dokument->name;
						}
						?>
				</div>
			</div>
		</div>
    </div>
	<hr>
</div>
</br>
