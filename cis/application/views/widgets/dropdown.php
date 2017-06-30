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
            $label = $value;
            $val = $value;
            if((is_array($value)) && (count($value) == 2))
            {
                $val = $value[0];
                $label = $value[1];
            }
            elseif(is_object($value))
            {
                $val = $value->id;
                $label = $value->description;
            }

            if((isset($object->{$data->name})) && ($object->{$data->name} == $label))
            {
                echo "<option value='$label' selected>$label</option>";
            }
            if((!isset($object->{$data->name})) && $data->defaultValue == $label)
            {
                echo "<option value='$label' selected>$label</option>";
            }
            else
            {
                echo "<option value='$label'>$label</option>";
            }
        }
        ?>
    </select>
</div>