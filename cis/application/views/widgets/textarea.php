<div class="form-group">
    <label for=""><?php echo $this->getPhrase($data->title, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></label>
    <textarea
        class="form-control"
        rows="5"
        id="<?php echo $data->name; ?>"
        title="<?php echo $this->getPhrase($data->description, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
        name="<?php echo $data->name; ?>"
        placeholder="<?php echo $this->getPhrase($data->placeholder, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
    ><?php
        if(isset($object->{$data->name}))
        {
            echo $object->{$data->name};
        }
        else
        {
            echo $data->defaultValue;
        }
        ?></textarea>
</div> 