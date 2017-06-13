<?php
$udf = $this->config->item("udf_container_personal_data");
var_dump($udf);

if(is_array($udf) && $udf["active"] == true)
{
    ?>
    <div class="<?php echo $udf["className"]; ?>">
        <h3><?php echo $this->getPhrase("summary/PersonalUserDefinedFields", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></h3>
        <div class="row form-row">
            <div class="col-sm-12">
                <div class="form-group">

                </div>
            </div>
        </div>
    </div>
    <?php
}
?>