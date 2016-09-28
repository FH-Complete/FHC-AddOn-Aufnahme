<?php
/**
 * ./cis/application/views/bewerbung.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $sprache);
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>
<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));

foreach ($studiengaenge as $studiengang) {
	$data["studiengang"] = $studiengang;

?>
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load_views('view_bewerbung_studiengang', $data); ?>
            </div>
        </div>
        <div class="row">
            <div id="<?php echo $studiengang->studiengang_kz; ?>" class="collapse <?php echo (isset($studiengang_kz) && ($studiengang_kz == $studiengang->studiengang_kz)) ? "in" : ""?>">
                <div class="col-sm-4 navigation">
                    <?php echo
	$this->template->widget(
		"person_nav",
		array(
			'aktiv' => 'personalData',
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

                    <?php $this->load_views('view_bewerbung', $data); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$this->load->view('templates/footer');
?>

<script type="text/javascript">
    function confirmStorno(studiengang_kz)
    {
		if(confirm("<?php echo $this->getPhrase("Bewerbung/StornoConfirmation", $sprache); ?>"))
		{
			window.location.href = "<?php echo base_url($this->config->config["index_page"]."/Bewerbung/storno/") ?>" + "/"+ studiengang_kz;
		}
    }
	
	function deleteDocument(dms_id, studienplan_id)
	{	
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/deleteDocument"); ?>',
			type: 'POST',
			data: {
				"dms_id": dms_id
			},
			cache: false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				console.log(data);
				if(data.error !== 0)
				{
					//TODO display error
				}
				else
				{
					$("#"+data.dokument_kurzbz+"Upload_"+studienplan_id).show();
					$("#"+data.dokument_kurzbz+"Delete_"+studienplan_id).html("");
					$('#'+data.dokument_kurzbz+'FileUpload_'+studienplan_id).parent().show();
					$('#'+data.dokument_kurzbz+'Progress_'+studienplan_id).show();
					$("#"+data.dokument_kurzbz+"_hochgeladen_"+studienplan_id).html("<?php echo $this->lang->line('person_formDokumentupload_keinDokHochgeladen'); ?>");
					$("#"+data.dokument_kurzbz+"_nachgereicht_"+studienplan_id).prop("disabled", false);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
	}
</script>
