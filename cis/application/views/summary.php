<?php
/**
 * ./cis/application/views/summary.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'summary'), $sprache);
$this->load->view('templates/cookieHeader');
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung', 'numberOfUnreadMessages'=>$numberOfUnreadMessages)); 

?>
	<div id="backToApplication">
		<span class="arrowLeft"></span><span><a href="<?php  echo base_url($this->config->config["index_page"]."/Bewerbung");?>"><?php echo $this->lang->line("aufnahme/backToApplications"); ?></a></span>
	</div>
<?php

foreach ($studiengaenge as $studiengang)
{
	$data["studiengang"] = $studiengang;

?>
    <div class="row stg-row">
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
					<a href="<?php echo base_url($this->config->config["index_page"]."/Send?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id); ?>">
						<button class="btn btn-primary icon-next pull-right"><?php echo $this->lang->line("summary_next"); ?></button>
					</a>
            </div>
        </div>
    </div>
	<?php } ?>
</div>
<script type="text/javascript">
	function confirmStorno(studiengang_kz)
    {
		if(confirm("<?php echo $this->getPhrase("Bewerbung/StornoConfirmation", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>"))
		{
			window.location.href = "<?php echo base_url($this->config->config["index_page"]."/Bewerbung/storno/") ?>" + "/"+ studiengang_kz;
		}
    }
	
	$(document).ready(function(){
		$(".infotext").each(function(i,v){
			var studiengang_kz = $(v).attr("studiengang_kz");
			var studienplan_id = $(v).attr("studienplan_id");
			checkDataCompleteness(studiengang_kz, studienplan_id);
		});
	});
	
	function checkDataCompleteness(studiengang_kz, studienplan_id)
	{
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Bewerbung/checkDataCompleteness"); ?>?studiengang_kz='+studiengang_kz,
			type: 'GET',
			cache: false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				if((data.person==false) ||(data.adresse==false) ||(data.dokumente==false) ||(data.kontakt==false) ||(data.zustelladresse==false))
				{
					$("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/unvollständig'); ?>");
				}
				else
				{
					checkDocuments(studiengang_kz, studienplan_id);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
	}
	
	function checkDocuments(studiengang_kz, studienplan_id)
	{
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Dokumente/areDocumentsComplete"); ?>',
			type: 'POST',
			data: {
				studiengang_kz: studiengang_kz
			},
			cache: false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				if((data.complete !== undefined) && (data.complete == false))
				{
					$("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/unvollständig'); ?>");
				}
				else
				{
					if(data.abgeschickt == false)
					{
						$("#infotext_"+studienplan_id).html("<?php echo $this->lang->line('aufnahme/nochNichtAbgeschickt'); ?>");
					}
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

<?php
$this->load->view('templates/footer');
