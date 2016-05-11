<h2><?php echo $this->lang->line('studiengaenge/bachelor'); ?></h2>

<?php
foreach ($studiengaenge as $stg) {
    if ($stg->typ == "b") {
        ?>
        <a data-toggle='collapse' data-target='#<?php echo $stg->studiengang_kz; ?>'><?php echo $stg->studiengangbezeichnung ?></a></br>
        <div id="<?php echo $stg->studiengang_kz; ?>" class='collapse'>
            <div class="row">
                <div class="col-sm-3 col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/abschluss') ?>: </div><div class="col-sm-6">Bachelor of Science in Engineering (BSc)</div>
            </div>
            <div class="row">
                <div class="col-sm-3  col-sm-offset-2"><?php echo $this->lang->line('studiengaenge/dauer'); ?>: </div><div class="col-sm-6">6 Semester</div> 
            </div>
            
            <div class="row">
                <div class="col-sm-3  col-sm-offset-5"><a href="<?php echo base_url("index.dist.php/Bewerbung/studiengang/".$stg->studiengang_kz) ?>"><button type="button" class="btn btn-sm">Jetzt bewerben!</button></a></div>
            </div>
        </div>
        <?php
    }
}
