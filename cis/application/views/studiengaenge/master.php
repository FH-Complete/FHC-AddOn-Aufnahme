<h2 class="master_header"><?php echo $this->lang->line('studiengaenge/master'); ?></h2>

<?php
foreach ($studiengaenge as $stg) 
{
    if ($stg->typ == "m")
        {
        
        foreach($stg->studienplaene as $studienplan)
        {
        ?>
        <a class="collapsed" data-toggle='collapse' data-target='#<?php echo $stg->studiengang_kz; ?>'><?php echo $stg->studiengangbezeichnung ?> (<?php echo $studienplan->orgform_kurzbz; ?>)</a></br>
        <div id="<?php echo $stg->studiengang_kz; ?>" class='collapse'>
            <div class="row">
                <div class="col-sm-3 col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/abschluss') ?>: </div><div class="col-sm-6">Bachelor of Science in Engineering (BSc)</div>
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/dauer'); ?>: </div><div class="col-sm-6"><?php echo $studienplan->regelstudiendauer; ?> Semester</div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2">
                    <?php echo $this->lang->line('studiengaenge/orgform'); ?>:
                </div>
                <div class="col-sm-6">
                    <?php foreach($orgform as $of)
                    {
                        if($of->orgform_kurzbz == $studienplan->orgform_kurzbz)
                        {
                            echo $of->bezeichnung;
                        }
                    } ?>
                </div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/studienplaetze'); ?>: </div><div class="col-sm-6"></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/studeingebuehr'); ?>: </div><div class="col-sm-6"></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/bewerbungsfrist'); ?>: </div><div class="col-sm-6"></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/aufnahmetermine'); ?>: </div><div class="col-sm-6"></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/studienbeginn'); ?>: </div><div class="col-sm-6"><?php echo $studiensemester->start; ?></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/weiterführend'); ?>: </div><div class="col-sm-6"></div> 
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-5"><a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/studiengang/".$stg->studiengang_kz."/".$studienplan->studienplan_id) ?>"><button type="button" class="btn btn-sm">Jetzt bewerben!</button></a></div>
            </div>
        </div>
        <?php
        }
    }
}
