<?php
/**
 * ./cis/application/views/studiengaenge.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'studiengaenge'), $sprache);
$this->load->view('templates/metaHeader');
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Studiengänge'));
if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

    <?php $this->load_views('view_studiengaenge'); ?>


</div>
<?php
$this->load->view('templates/footer');
?>
<script type="text/javascript">
    $(document).ready(function(){
//	$(".frist").each(function(i,v){
//	   if($(v).attr("studienplan_id"))
//	   {
//	       var id = $(v).attr("studienplan_id");
//	       $("#button_"+id).prop("disabled", true);
//
//	       $("#button_"+id).attr("title", "Derzeit keine Bewerbung möglich!");
//	       $("#button_"+id).tooltip();
//	   }
//	});
//
	$(".icon-bewerben").tooltip();
    });
</script>
