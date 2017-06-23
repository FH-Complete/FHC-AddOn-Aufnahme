<div class="form-group">
    <label><?php echo $this->getPhrase($data->title, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?></label>
    <select
        id="<?php echo $data->name; ?>"
        class="form-control"
        <?php echo $data->type == 'multipledropdown' ? 'multiple' : ''; ?>
        title="<?php echo $this->getPhrase($data->description, $sprache, $studiengang->oe_kurzbz, $studiengang->studienplaene[0]->orgform_kurzbz); ?>"
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
        name="<?php echo $data->name; ?>"
    >
        <?php
        foreach($data->listValues->enum as $key => $value)
        {
            if((isset($object->{$data->name})) && ($object->{$data->name} == $value))
            {
                var_dump('test');
                echo "<option value='$value' selected>$value</option>";
            }
            if((!isset($object->{$data->name})) && $data->defaultValue == $value)
            {
                echo "<option value='$value' selected>$value</option>";
            }
            else
            {
                echo "<option value='$value'>$value</option>";
            }
        }
        ?>
    </select>
</div>