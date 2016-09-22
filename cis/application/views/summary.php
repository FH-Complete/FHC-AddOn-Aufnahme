<?php
/**
 * ./cis/application/views/summary.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'summary'), $sprache);
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung')); ?>
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_bewerbung_studiengang'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 navigation">
            <?php echo
$this->template->widget(
	"person_nav",
	array(
		'aktiv' => 'summary',
		"href"=>array(
			"send"=>site_url("/Send?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
			"summary"=>site_url("/Summary?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
			"requirements"=>site_url("/Requirements?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
			"personalData"=>site_url("/Bewerbung?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id)
		)
	)
); ?>
        </div>
        <div class="col-sm-8">
            <div role="tabpanel" class="tab-pane" id="summary">
                <h1 id="summaryHeader"><?php echo $this->lang->line("summary_header"); ?></h1>
                <?php $this->load_views('view_summary'); ?>
		<a href="<?php echo base_url($this->config->config["index_page"]."/Send?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id); ?>"><button class="btn btn-primary icon-next"><?php echo $this->lang->line("summary_next"); ?></button></a>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('templates/footer');
