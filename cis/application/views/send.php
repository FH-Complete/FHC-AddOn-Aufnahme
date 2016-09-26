<?php
/**
 * ./cis/application/views/send.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'send'), $sprache);
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
foreach ($studiengaenge as $studiengang)
{
	$data["studiengang"] = $studiengang;

?>
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_bewerbung_studiengang', $data); ?>
        </div>
    </div>
    <div id="<?php echo $studiengang->studiengang_kz; ?>" class="row collapse <?php echo (isset($studiengang_kz) && ($studiengang_kz == $studiengang->studiengang_kz)) ? "in" : ""?>">
        <div class="col-sm-4 navigation">
            <?php echo
$this->template->widget(
	"person_nav",
	array(
		'aktiv' => 'send',
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

            <?php $this->load_views('view_send'); ?>
        </div>
    </div>
	<?php } ?>
</div>

<?php
$this->load->view('templates/footer');
