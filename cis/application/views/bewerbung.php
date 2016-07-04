<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $language);
// This is an example to show that you can load stuff from inside the template file
echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
?>

<div class="container">
    <?php foreach($studiengaenge as $studiengang){ 
        $data["studiengang"] = $studiengang;
        
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load_views('view_bewerbung_studiengang', $data); ?>
            </div>
        </div>
        <div class="row">
            <div id="<?php echo $studiengang->studiengang_kz; ?>" class='collapse'>
                <div class="col-sm-3">
                    <?php echo 
                        $this->template->widget(
                            "person_nav",
                            array(
                                'aktiv' => 'Person',
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
    $(document).ready(function(){
	$(".zustelladresse").click(function(event)
	{
	    var id = $(event.currentTarget).attr("studienplan_id");
	    if($(event.currentTarget).prop("checked"))
	    {
		$("#zustelladresse_"+id).show();
	    }
	    else
	    {
		$("#zustelladresse_"+id).hide();
	    }
	});

	$("#adresse_nation").on("change", function(event){
	   toggleAdresse();
	});
	
	toggleAdresse();
    });
    
    function toggleAdresse()
    {
	var code = $("#adresse_nation option:selected").val();
	if(code === "A")
	{
	    hideElement($("#plz").closest(".row"));
	    hideElement($("#ort").closest(".row"));
	    hideElement($("#bundesland").closest(".row"));
	    showElement($("#plzOrt").closest(".row"));
	    //TODO exchange form inputs
	}
	else
	{
	    hideElement($("#plzOrt").closest(".row"));
	    showElement($("#plz").closest(".row"));
	    showElement($("#ort").closest(".row"));
	    showElement($("#bundesland").closest(".row"));
	}
    }
    
    function hideElement(ele)
    {
	$(ele).hide();
    }
    
    function showElement(ele)
    {
	$(ele).show();
    }
</script>