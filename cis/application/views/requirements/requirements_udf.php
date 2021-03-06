<?php
$udf_config = $this->config->item("udf_container_requirements");

if(is_array($udf_config) && $udf_config["active"] == true)
{
    ?>
    <div class="<?php echo $udf_config["className"]; ?>">
        <h3><?php echo $this->getPhrase("ZGV/UserDefinedFields", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></h3>
        <?php
        if(isset($udfs) && is_array($udfs))
        {
            $renderedElements = 0;
            $renderedElementsArray = array();
            foreach($udfs as $udf)
            {
                if(isset($udf_config["udfs"]) && in_array($udf->name, $udf_config["udfs"]) && (!isset($renderedElementsArray[$udf->name])))
                {
                    if(($renderedElements % 2 == 0))
                    {
                        echo '</div>';
                    }
                    if(($renderedElements % 2 == 0) || ($renderedElements == 0))
                    {
                        echo '<div class="row form-row">';
                    }
                    echo '<div class="col-sm-6">'.$this->template->widget("userdefinedfield", array('data' => $udf, 'object' => $prestudent)).'</div>';
                    $renderedElements++;
                    $renderedElementsArray[$udf->name] = true;
                }
            }
        }
        ?>
    </div>
    <?php
}
?>
