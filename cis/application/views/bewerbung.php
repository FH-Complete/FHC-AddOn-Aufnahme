<?php
/**
 * ./cis/application/views/bewerbung.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $sprache);
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

foreach ($studiengaenge as $studiengang) {
	$data["studiengang"] = $studiengang;

?>
        <div class="row stg-row">
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
					<div role="tabpanel" class="tab-pane" id="daten">
						<?php $this->load_views('view_bewerbung', $data); ?>
						<div class="row form-row">
							<div class="col-sm-4">
								<div class="form-group">
									<?php 
									$data = array("content"=>(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $this->lang->line("person_weiter"):$this->lang->line("person_speichern"), "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit");
									//(isset($bewerbung_abgeschickt) && ($bewerbung_abgeschickt == true)) ? $data["disabled"] = "disabled" : false;
									echo form_button($data); ?>
								</div>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
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
		if(confirm("<?php echo $this->getPhrase("Bewerbung/StornoConfirmation", $sprache, $studiengang->oe_kurzbz, $studiengang->studienplan->orgform_kurzbz); ?>"))
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
					$("#"+data.dokument_kurzbz+"_logo_"+studienplan_id).html("");
					toggleDocumentsComplete(studienplan_id);

                    $("#"+data.dokument_kurzbz+"_logo_"+studienplan_id).parent().addClass("has-error");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
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
