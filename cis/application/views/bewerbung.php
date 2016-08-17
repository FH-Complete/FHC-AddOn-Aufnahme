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
    $(document).ready(function(){
	
	$(".zustelladresse").each(function(i,v){
	   if($(v).prop("checked"))
	   {
	       var id = $(v).attr("studienplan_id");
	       $("#zustelladresse_"+id).show();
	   }
	});
	
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
	
	checkDataCompleteness();
	
    });
    
    function toggleAdresse()
    {
	var code = $("#adresse_nation option:selected").val();
	if(code === "A")
	{
	    hideElement($("#ort_input"));
	    showElement($("#ort_dropdown"));
	    var plz = $("#plz").val();
	    loadOrtData(plz, $("#ort_dropdown"));
	}
	else
	{
	    showElement($("#ort_input"));
	    hideElement($("#ort_dropdown"));
	}
    }
    
    function toggleZustellAdresse()
    {
	var code = $("#zustelladresse_nation option:selected").val();
	if(code === "A")
	{
	    hideElement($("#zustell_ort_input"));
	    showElement($("#zustell_ort_dropdown"));
	    var plz = $("#zustell_plz").val();
	    loadOrtData(plz, $("#zustell_ort_dropdown"));
	}
	else
	{
	    showElement($("#zustell_ort_input"));
	    hideElement($("#zustell_ort_dropdown"));
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

    function checkDataCompleteness()
    {
	//TODO hide Authorization
	$.ajax({
	    method: "GET",
	    url: "<?php echo($this->config->item('fhc_api')['server']);?>person/person/person?person_id=<?php echo $person->person_id; ?>"
	}).done(function(data){
	    if(data.error === 0)
	    {
		var person = data.retval[0];
		if(_isPersonDataComplete(person))
		{
		    $(".personalData").addClass("complete");
		}
		else
		{
		    $(".personalData").addClass("incomplete");
		}
	    }
	});
	
	console.log('<?php echo base64_encode($this->config->item('fhc_api')['http_user'].":".$this->config->item('fhc_api')['http_pass']); ?>');
    }
    
    function _isPersonDataComplete(person)
    {
	if(person.vorname === null)
	{
	    return false;
	}

	if(person.nachname === null)
	{
	    return false;
	}

	if(person.gebdatum === null)
	{
	    return false;
	}

	if(person.gebort === null)
	{
	    return false;
	}

	if(person.staatsbuergerschaft === null)
	{
	    return false;
	}

	if(person.geburtsnation === null)
	{
	    return false;
	}

	if(person.svnr === null)
	{
	    return false;
	}

	if((person.geschlecht !== "m") && (person.geschlecht !== "w"))
	{
	    return false;
	}

	return true;
    }
    
    function confirmStorno()
    {
	if(confirm("<?php echo $this->getPhrase("Bewerbung/StornoConfirmation", $sprache); ?>"))
	{
	    window.location.href = "<?php echo base_url($this->config->config["index_page"]."/Bewerbung/storno/$studiengang->studiengang_kz") ?>";
	}
    }
    
    function loadOrtData(plz, ele)
    {
	console.log(ele);
	$.ajax({
	    method: "GET",
	    url: "<?php echo($this->config->item('fhc_api')['server']);?>codex/gemeinde/gemeinde?plz="+plz
	}).done(function(data){
	    console.log(data);
	    console.log($(ele).find("select"));
	    if(data.error === 0)
	    {
		$(ele).find("select").empty();
		$.each(data.retval, function(i, v){
		    $(ele).find("select").append("<option value='"+v.gemeinde_id+"'>"+v.ortschaftsname+"</option>");
		});
	    }
	});
    }
</script>