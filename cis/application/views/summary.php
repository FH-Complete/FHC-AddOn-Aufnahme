<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'summary'), $language);
// This is an example to show that you can load stuff from inside the template file
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_bewerbung_studiengang'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?php echo $this->template->widget("person_nav", array('aktiv' => 'Person')); ?>
        </div>
        <div class="col-sm-8">
            <div role="tabpanel" class="tab-pane" id="summary">
                <h1><?php echo $this->lang->line("summary_header"); ?></h1>
                <?php $this->load_views('view_summary'); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('templates/footer');
