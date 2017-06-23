<?php
$udf_config = $this->config->item("udf_container_requirements");

if(is_array($udf_config) && $udf_config["active"] == true)
{
    ?>
    <div class="<?php echo $udf_config["className"]; ?>">
        <legend><?php echo $this->getPhrase("summary/RequirementsUserDefinedFields", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></legend>
        <?php
        if(isset($udfs) && is_array($udfs))
        {
            $renderedElements = 0;
            foreach($udfs as $udf)
            {
                if(isset($udf_config["udfs"]) && in_array($udf->name, $udf_config["udfs"]))
                {
                    if (($renderedElements % 2 == 0))
                    {
                        echo '</div>';
                    }
                    if (($renderedElements % 2 == 0) || ($renderedElements == 0))
                    {
                        echo '<div class="row">';
                    }
                    echo '<div class="col-sm-6">
                          <div class="col-sm-6">' .
                        $udf->name
                        . '</div>
                          <div class="col-sm-6 '.(!isset($prestudent->{$udf->name}) ? "incomplete" : "").'">' .
                        (isset($prestudent->{$udf->name}) ? (($prestudent->{$udf->name} === true) ? "true" : (($prestudent->{$udf->name} === false) ? "false" : $prestudent->{$udf->name})) : $this->lang->line("summary_unvollstaendig"))
                        . '</div></div>';
                    $renderedElements++;
                }
            }
        }
        ?>
    </div>
    <?php
}
?>
