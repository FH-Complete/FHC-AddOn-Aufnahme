<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'requirements'), $language);

if (isset($error) && ($error->error === true))
    echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
    ?>
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
                        'aktiv' => 'requirements',
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
            <div role="tabpanel" class="tab-pane" id="requirements">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $this->getPhrase("ZGV/AdmissionRequirements", $sprache); ?>
                    </div>
                </div>
		<?php $this->load_views('view_requirements'); ?>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
			<?php echo form_button(array("content"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden", "type"=>"submit")); ?>
                    </div>
                </div>
	    </div>
	</div>
	<?php echo form_close(); ?>
    </div>
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
    function uploadFiles(document_kurzbz, studienplan_id, submit)
    {
	// START A LOADING SPINNER HERE

	// Create a formdata object and add the files
	var data = new FormData();
	$.each(files, function(key, value)
	{
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
	    success: function(data, textStatus, jqXHR)
	    {
		if(data.success === true)
		{
		    // Success
		    $("#"+document_kurzbz+'_'+studienplan_id).after("<span><?php echo $this->lang->line('requirements_UploadErfolgreich');?></span>");
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
	    error: function(jqXHR, textStatus, errorThrown)
	    {
		// Handle errors here
		console.log('ERRORS: ' + textStatus);
		// STOP LOADING SPINNER
	    }
	});
    }
</script>

<?php
$this->load->view('templates/footer');
