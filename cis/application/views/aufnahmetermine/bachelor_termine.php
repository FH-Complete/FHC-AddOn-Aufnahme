<?php
/**
 * ./cis/application/views/aufnahmetermine/bachelor_termine.php
 *
 * @package default
 */


if (isset($anmeldeMessage))
	echo '<div class="alert alert-danger" role="alert">'.$anmeldeMessage.'</div>';

if (!empty($studiengaenge))
{
	foreach ($studiengaenge as $stg)
	{
		// If the type is Bachelor and if the application is sent
		if ($stg->typ == 'b' && $stg->prestudentstatus[0]->bewerbung_abgeschicktamum != null)
		{
?>
	    <h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplaene[0]->orgform_kurzbz; ?>)</h3>
	    <div id="<?php echo $stg->studiengang_kz; ?>">
		<div class="row">
		    <div class="col-sm-12">
                <?php echo $this->getPhrase("Termine/BewerbungIstEingelangt", $sprache, $stg->oe_kurzbz, $stg->studienplaene[0]->orgform_kurzbz); ?>
		    </div>
		</div>

		<?php
			if (isset($reihungstests[$stg->studiengang_kz]) && is_object($reihungstests[$stg->studiengang_kz]))
			{
                foreach($reihungstests[$stg->studiengang_kz]->reihungstest as $stufe => $reihungstest)
                {
                    if($stufe <= $prestudentStufen[$stg->studienplaene[0]->studienplan_id])
                    {
                        $selectdReihungstest = 0;
                        if (isset($registeredReihungstests[$stg->studiengang_kz][$stufe]))
                        {
                            $selectdReihungstest = $registeredReihungstests[$stg->studiengang_kz][$stufe];
                        }

                        ?>
                        <h4><?php echo $this->getPhrase("Termine/Stufe" . $stufe, $sprache, $stg->oe_kurzbz, $stg->studienplaene[0]->orgform_kurzbz); ?></h4>

                        <div id="<?php echo $selectdReihungstest ?>_select"
                             class="row" <?php if (($selectdReihungstest != 0) && ($reihungstestData[$selectdReihungstest]->stufe == $stufe)) echo 'style="display: none;"' ?>>
                            <?php
                            echo form_open("/Aufnahmetermine/register/" . $stg->studiengang_kz . "/" . $stg->studienplaene[0]->studienplan_id, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin"));
                            ?>
                            <div class="col-sm-4">
                                <div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
                                    <?php
                                    echo form_dropdown(
                                        "rtTermin",
                                        $reihungstest,
                                        $selectdReihungstest == 0 ? null : $selectdReihungstest,
                                        array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")
                                    );
                                    ?>
                                    <?php echo form_error("rtTermin"); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?php echo form_button(array("content" => "Absenden", "name" => "submit_btn", "class" => "btn btn-primary icon-absenden", "type" => "submit")); ?>
                                </div>
                            </div>


                            <?php
                            echo form_close();
                            ?>
                        </div>
                        <?php
                        if (($selectdReihungstest != 0) && ($reihungstestData[$selectdReihungstest]->stufe == $stufe))
                        {
                            ?>
                            <div id="<?php echo $selectdReihungstest ?>_termin" class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <span class="selectedTerminHeader"><?php echo $this->lang->line("termine/gewaehlterTermin"); ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
											<span class="selectedTermin">
		<?php
        echo $reihungstest[$selectdReihungstest];
        ?>
											</span>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group" <?php echo (isset($prestudentStufen[$stg->studienplaene[0]->studienplan_id]) && ($prestudentStufen[$stg->studienplaene[0]->studienplan_id] != $stufe)) ? "style='display: none'" : ""; ?>>
                                                <button type="button" class="btn btn-primary icon-bewerben"
                                                        onclick="showSelectBox(<?php echo $selectdReihungstest; ?>);">
                                                    <?php echo $this->lang->line("termine/terminAendern"); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
				}
			}
			else
			{
				echo $this->lang->line("termine/keineTermineVorhanden");
			}
			echo '</div>';
		}
	}
}
?>

<script type="text/javascript">
    function showSelectBox(rtId)
    {
        $("#"+rtId+'_select').show();
        $("#"+rtId+'_termin').hide();
    }

</script>
