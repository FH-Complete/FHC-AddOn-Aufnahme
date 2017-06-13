<div class="checkbox">
    <label>
        <input
            type="checkbox"
            value=""
            title="<?php echo $data->title; ?>"
            <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
        >
        <?php echo $data->name; ?>
    </label>
</div>