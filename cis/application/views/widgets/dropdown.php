<div class="form-group">
    <label><?php echo $data->name; ?></label>
    <select
        class="form-control"
        multiple="<?php echo $data->type == 'multipledropdown' ? 'true' : 'false'; ?>"
        title="<?php echo $data->title; ?>"
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
    >
        <?php
        foreach($data->listValues->enum as $key => $value)
        {
            if($data->defaultValue == $value)
            {
                echo "<option value='$key' selected>$value</option>";
            }
            else
            {
                echo "<option value='$key'>$value</option>";
            }
        }
        ?>
    </select>
</div>