<h2><?php echo $this->lang->line('studiengaenge/master'); ?></h2>

<?php

foreach($studiengaenge as $stg)
{
    if($stg->typ == "m")
    {
        echo $stg->studiengangbezeichnung."</br>";
    }
}

