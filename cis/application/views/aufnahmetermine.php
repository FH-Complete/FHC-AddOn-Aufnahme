<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'termine'), $language);
// This is an example to show that you can load stuff from inside the template file
echo $this->template->widget("menu", array('aktiv' => 'Aufnahmetermine'));

if (isset($error) && ($error->error === true))
    echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
   <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_aufnahmetermine'); ?>
        </div>
    </div>
</div>

<?php
$this->load->view('templates/footer');