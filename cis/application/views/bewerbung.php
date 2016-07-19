<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $language);

if (isset($error) && ($error->error === true))
    echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>
<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
    
    foreach($studiengaenge as $studiengang){ 
        $data["studiengang"] = $studiengang;
        
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load_views('view_bewerbung_studiengang', $data); ?>
            </div>
        </div>
        <div class="row">
            <div id="<?php echo $studiengang->studiengang_kz; ?>" class='collapse'>
                <div class="col-sm-3 navigation">
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
    $(document).ready(function(){
	
	$(".zustelladresse").each(function(i,v){
	   if($(v).prop("checked"))
	   {
	       var id = $(v).attr("studienplan_id");
	       $("#zustelladresse_"+id).show();
	   }
	});
	
	
	if($(".zustelladresse").prop("checked"))
	{
	    console.log($(".zustelladresse"));
	}
	
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
	
	$("#zustelladresse_nation").on("change", function(event){
	   toggleZustellAdresse();
	});
	
	toggleAdresse();
	toggleZustellAdresse();
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
	}
	else
	{
	    hideElement($("#plzOrt").closest(".row"));
	    showElement($("#plz").closest(".row"));
	    showElement($("#ort").closest(".row"));
	    showElement($("#bundesland").closest(".row"));
	}
    }
    
    function toggleZustellAdresse()
    {
	var code = $("#zustelladresse_nation option:selected").val();
	if(code === "A")
	{
	    hideElement($("#zustell_plz").closest(".row"));
	    hideElement($("#zustell_ort").closest(".row"));
	    hideElement($("#zustell_bundesland").closest(".row"));
	    showElement($("#zustell_plzOrt").closest(".row"));
	}
	else
	{
	    hideElement($("#zustell_plzOrt").closest(".row"));
	    showElement($("#zustell_plz").closest(".row"));
	    showElement($("#zustell_ort").closest(".row"));
	    showElement($("#zustell_bundesland").closest(".row"));
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