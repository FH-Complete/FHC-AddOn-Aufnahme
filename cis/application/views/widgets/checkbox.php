<div class="checkbox">
    <label>
        <input
            id="<?php echo $data->name; ?>"
            type="checkbox"
            value=""
            title="<?php echo $this->getPhrase($data->description, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
            <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
            <?php echo (((isset($object->{$data->name}) && ($object->{$data->name} == true))) ? 'checked' : (((isset($object->{$data->name}) && ($object->{$data->name} == false))) ? '' : (isset($data->defaultValue) && (($data->defaultValue == 'yes') || ($data->defaultValue == true)|| ($data->defaultValue == 'checked')) ? 'checked' : '')));
            ?>
            name="<?php echo $data->name; ?>"
        />
        <?php echo $this->getPhrase($data->title, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>
    </label>
</div>