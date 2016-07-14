<?php
if (isset($anmeldeMessage))
    echo '<div class="alert alert-danger" role="alert">'.$anmeldeMessage.'</div>';

foreach ($studiengaenge as $stg) 
{
    if ($stg->typ == "b")
        {
	
        ?>
	<h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3>
        <div id="<?php echo $stg->studiengang_kz; ?>">
            <div class="row">
                <div class="col-sm-12">
                    Ihre Bewerbung ist eingelangt, bitte wählen Sie einen Termin für das erste Aufnahmeverfahren aus:
                </div>
            </div>
            <h4>Erstes Aufnahmeverfahren</h4>
            <div class="row">
		<?php echo form_open("/Aufnahmetermine/register/".$stg->studiengang_kz, array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
                <div class="col-sm-4">
		    <?php 
		    if(!empty($reihungstests[$stg->studiengang_kz][1]))
		    {
		    ?>
		    <div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
			<?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][1], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
			<?php echo form_error("rtTermin"); ?>
		    </div>
		    <?php
		    }
		    else
		    {
			//TODO
			echo "keine Termine vorhanden";
		    }
		    ?>
                </div>
		
                <div class="col-sm-6">
		    <div class="form-group">
			<?php echo form_submit(array("value"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden")); ?>
		    </div>
		</div>
		<?php
		echo form_close();
		?>
            </div>
	    <?php 
	    if(!empty($reihungstests[$stg->studiengang_kz][2]))
	    {
	    ?>
            <h4>Zweites Aufnahmeverfahren</h4>
            <div class="row">
		<?php echo form_open("/Aufnahmetermine/register/", array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
                <div class="col-sm-4">
		    <?php 
		    if(!empty($reihungstests[$stg->studiengang_kz][2]))
		    {
		    ?>
                    <div class="form-group <?php echo (form_error("rtTermin") != "") ? 'has-error' : '' ?>">
			<?php echo form_dropdown("rtTermin", $reihungstests[$stg->studiengang_kz][2], isset($rt_person[$stg->studiengang_kz]) ? $rt_person[$stg->studiengang_kz] : null, array('id' => 'rtTermin', 'name' => 'rtTermin', "class" => "form-control")); ?>
			<?php echo form_error("rtTermin"); ?>
		    </div>
		    <?php
		    }
		    else
		    {
			//TODO
			echo "keine Termine vorhanden";
		    }
		    ?>
                </div>
                <div class="col-sm-6">
		    <div class="form-group">
			<?php echo form_submit(array("value"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden")); ?>
		    </div>
		</div>
		<?php
		echo form_close();
		?>
	    </div>
	    <?php
	    }
	    ?>
        </div>
        <?php
    }
}
