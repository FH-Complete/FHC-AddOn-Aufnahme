<?php
foreach ($studiengaenge as $stg) 
{
    if ($stg->typ == "b")
        {
        
        ?>
<a data-toggle='collapse' data-target='#<?php echo $stg->studiengang_kz; ?>'><h3>Bachelor / <?php echo $stg->bezeichnung ?> (<?php echo $stg->studienplan->orgform_kurzbz; ?>)</h3></a>
        <div id="<?php echo $stg->studiengang_kz; ?>" class='collapse'>
            <div class="row">
                <div class="col-sm-12">
                    Ihre Bewerbung ist eingelangt, bitte wählen Sie einen Termin für das erste Aufnahmeverfahren aus:
                </div>
            </div>
            <h4>Erstes Aufnahmeverfahren</h4>
            <div class="row">
                <div class="col-sm-4">
		    <?php 
		    if(!empty($reihungstests[$stg->studiengang_kz]))
		    {
		    ?>
                    <select>
			<?php
			
			foreach($reihungstests[$stg->studiengang_kz] as $rt) 
			{
			    if(($rt-stufe == "1"))
			    {
				echo "<option>".$rt->datum."</option>";
			    }
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
                <div class="col-sm-6"><a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/studiengang/".$stg->studiengang_kz."/".$stg->studienplan->studienplan_id) ?>"><button type="button" class="btn btn-sm">Absenden</button></a></div>
            </div>
            <div class="row">
                
            </div>
        </div>
        <?php
    }
}
