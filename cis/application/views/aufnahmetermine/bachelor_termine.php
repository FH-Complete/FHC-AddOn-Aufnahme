<?php
foreach ($studiengaenge as $stg) 
{
    if ($stg->typ == "b")
        {
        
        ?>
<a class="collapsed" data-toggle='collapse' data-target='#<?php echo $stg->studiengang_kz; ?>'><h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3></a>
        <div id="<?php echo $stg->studiengang_kz; ?>" class='collapse'>
            <div class="row">
                <div class="col-sm-12">
                    Ihre Bewerbung ist eingelangt, bitte wählen Sie einen Termin für das erste Aufnahmeverfahren aus:
                </div>
            </div>
            <h4>Erstes Aufnahmeverfahren</h4>
            <div class="row">
		<?php echo form_open("/Aufnahmetermine/register/", array("id" => "Aufnahmetermin", "name" => "Aufnahmetermin")); ?>
                <div class="col-sm-4">
		    <?php 
		    if(!empty($reihungstests[$stg->studiengang_kz][1]))
		    {
		    ?>
                    <select class="rtTermin" name="rtTermin">
			<?php
			
			foreach($reihungstests[$stg->studiengang_kz][1] as $rt) 
			{
			    echo "<option value='".$rt->reihungstest_id."'>".$rt->datum."</option>";
			}
                       ?>
                    </select>
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
			<?php echo form_submit(array("value"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
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
                    <select  class="rtTermin" name="rtTermin">
			<?php
			
			foreach($reihungstests[$stg->studiengang_kz][2] as $rt) 
			{
			    echo "<option value='".$rt->reihungstest_id."'>".$rt->datum."</option>";
			}
                       ?>
                    </select>
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
			<?php echo form_submit(array("value"=>"Absenden", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
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
