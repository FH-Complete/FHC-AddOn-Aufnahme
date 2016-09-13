<?php
/**
 * ./cis/application/views/bewerbung.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'person'), $language);

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
    $(document).ready(function(){


	checkDataCompleteness();

    });

    function checkDataCompleteness()
    {
	$.ajax({
	    method: "GET",
	    url: "<?php echo $this->config->item('fhc_api')['server'];?>person/person/person?person_id=<?php echo $person->person_id; ?>"
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
</script>
