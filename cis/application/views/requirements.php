<?php
/**
 * ./cis/application/views/requirements.php
 *
 * @package default
 */

$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'requirements'), $sprache);
$this->load->view('templates/metaHeader');

if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
		$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
		echo $this->template->widget("menu", array('aktiv' => 'Bewerbung', 'numberOfUnreadMessages'=>$numberOfUnreadMessages));

	foreach ($studiengaenge as $studiengang) {
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
					array (
						'aktiv' => 'requirements',
						"href"=>array (
							"send"=>site_url("/Send?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
							"summary"=>site_url("/Summary?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
							"requirements"=>site_url("/Requirements?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
							"personalData"=>site_url("/Bewerbung?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id)
						)
					)
				);
			?>
        </div>
        <div class="col-sm-8">
            <div role="tabpanel" class="tab-pane" id="requirements">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $this->getPhrase("ZGV/AdmissionRequirements", $sprache); ?>
                    </div>
                </div>
				<?php $this->load_views('view_requirements'); ?>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<?php echo form_button(array("content"=>$this->lang->line("requirements_speichern"), "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
            </div>
            
		</div>
    </div>
	<?php } ?>
</div>



<script type="text/javascript">

    $(document).ready(function() {
		$('input[type=file]').on('change', prepareUpload);
    });

    var files;

    function prepareUpload(event)
    {
		files = event.target.files;
    }

    // Catch the form submit and upload the files
    /*function uploadFiles(document_kurzbz, studienplan_id, submit)
    {
	// START A LOADING SPINNER HERE

		// Create a formdata object and add the files
		var data = new FormData();
		$.each(files, function(key, value) {
			data.append(document_kurzbz, value);
		});

		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/uploadFiles"); ?>',
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			success: function(data, textStatus, jqXHR) {
			
				if(data.success === true)
				{
					// Success
					$("#"+document_kurzbz+'_'+studienplan_id).after("<span><?php echo $this->lang->line('requirements_UploadErfolgreich');?></span>");
					$("#"+document_kurzbz+'_hochgeladen').html("<span><?php echo $this->lang->line('requirements_DokHochgeladen'); ?></span>");
					$("#"+document_kurzbz+"_nachgereicht").prop("checked", false);
					$("#"+document_kurzbz+"_nachgereicht").prop("disabled", true);
					$("#"+document_kurzbz+"_nachreichenAnmerkung").parent().hide();
					$("#"+document_kurzbz+"_nachreichenDatum").parent().hide();
					if(submit === true)
					{
						$("#"+document_kurzbz+'_'+studienplan_id).closest("form").submit();
					}
				}
				else
				{
					// Handle errors here
					$("#"+document_kurzbz+'_'+studienplan_id).after("<span><?php echo $this->lang->line('requirements_UploadError');?></span>");
					console.log('ERRORS: ' + data.error);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
    }*/
	
	function deleteDocument(dms_id, studienplan_id)
	{	
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Requirements/deleteDocument"); ?>',
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
					$("#"+data.dokument_kurzbz+"_hochgeladen").html("<?php echo $this->lang->line('requirements_keinDokHochgeladen'); ?>");
					$("#"+data.dokument_kurzbz+"_nachgereicht_"+studienplan_id).prop("disabled", false);
					$("#"+data.dokument_kurzbz+"_logo_"+studienplan_id).children().hide();
					toggleDateField(studienplan_id);
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