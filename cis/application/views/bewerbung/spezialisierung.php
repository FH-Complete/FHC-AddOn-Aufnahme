<?php
$spez_phrase = $this->getPhrase("Aufnahme/Spezialisierung", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz);
if(($spez_phrase != null) && (substr($spez_phrase,0,3) !== "<i>"))
{
?>
	<legend>
		<?php echo $this->getPhrase("Personal/SpezialisierungHeader", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
		<div class="pull-right">
			<span class="incomplete"><?php echo ((isset($complete)) && (!$complete["person"])) ? $this->lang->line("aufnahme/unvollstaendig") : ""; ?></span>
		</div>
	</legend>
	<div class="row form-row">
		<div class="col-sm-12">
			<div class="form-group">
				<?php echo $this->getPhrase("Personal/SpezialiserungText", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>
			</div>
		</div>
	</div>
	<div class="row form-row">
		<div class="col-sm-10">
			<?php
				if(!empty($spezialisierung[$studiengang->studiengang_kz]))
				{
					if((!isset($bewerbung_abgeschickt)) || ($bewerbung_abgeschickt!=true))
					{
					?>
					<a href="<?php echo base_url($this->config->config["index_page"]."/Requirements/deleteSpezialisierung/".$spezialisierung[$studiengang->studiengang_kz]->notiz_id."/".$studiengang->studiengang_kz); ?>"><button type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-trash"></span></button></a>
					<?php
					}
					$spez = explode(";",$spezialisierung[$studiengang->studiengang_kz]->text);
					echo "<ol class='list-group'>";
					foreach($spez as $key=>$item)
					{
						echo "<li class='list-group-item'>".($key+1).". ".$item."</li>";
					}
					echo "</ol>";
				}
				else
				{
					echo $spez_phrase;
				}
			?>
		</div>
	</div>
<?php
}
?>
