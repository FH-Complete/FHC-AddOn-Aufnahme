<div class="form-group">
    <label for=""><?php echo $data->name; ?></label>
    <textarea
        class="form-control"
        rows="5"
        id=""
        title="<?php echo $data->title; ?>">
        <?php echo (isset($data->validation->required) && ($data->validation->required == true)) ? 'required' : ''; ?>
    </textarea>
</div> 