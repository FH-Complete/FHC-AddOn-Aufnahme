<div class="form-group">
    <label for=""><?php echo $data->name; ?></label>
    <input
        type="text"
        class="form-control"
        id=""
        placeholder="<?php echo $data->placeholder; ?>"
        value="<?php echo $data->defaultValue; ?>"
        title="<?php echo $data->title; ?>"
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
    >
</div>