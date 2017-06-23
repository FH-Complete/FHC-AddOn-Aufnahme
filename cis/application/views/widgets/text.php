<div class="form-group">
    <label for=""><?php echo $this->getPhrase($data->title, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></label>
    <input
        type="text"
        class="form-control"
        id="<?php echo $data->name; ?>"
        placeholder="<?php echo $this->getPhrase($data->placeholder, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
        value="<?php echo (isset($object->{$data->name})) ? $object->{$data->name} : $data->defaultValue;  ?>"
        title="<?php echo $this->getPhrase($data->description, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
        name="<?php echo $data->name; ?>"
    />
</div>