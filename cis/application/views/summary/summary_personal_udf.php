<?php
$udf_config = $this->config->item("udf_container_personal_data");

if(is_array($udf_config) && $udf_config["active"] == true)
{
    ?>
    <div class="<?php echo $udf_config["className"]; ?>">
        <legend><?php echo $this->getPhrase("summary/PersonalUserDefinedFields", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></legend>
        <?php
        if(isset($udfs) && is_array($udfs))
        {
            $renderedElements = 0;
            $renderedElementsArray = array();
            foreach($udfs as $udf)
            {
                if(isset($udf_config["udfs"]) && in_array($udf->name, $udf_config["udfs"]) && (!isset($renderedElementsArray[$udf->name])))
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
                          <div class="col-sm-6 '.((!isset($prestudent->{$udf->name}) && (!isset($person->{$udf->name}))) ? "incomplete" : "").'">' .
                        (isset($prestudent->{$udf->name}) ? (($prestudent->{$udf->name} === true) ? "true" : (($prestudent->{$udf->name} === false) ? "false" : $prestudent->{$udf->name})) : (isset($person->{$udf->name}) ? (($person->{$udf->name} === true) ? "true" : (($person->{$udf->name} === false) ? "false" : $person->{$udf->name})) : $this->lang->line("summary_unvollstaendig")))
                        . '</div></div>';
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