<?php 
	$this->load->view('templates/header');
	//$this->load->view('menu', 'person');
	$this->lang->load(array('aufnahme', 'overview'), $language);
?>


<div class="container">
    <?php 
		
	    echo $this->template->widget("menu", array('aktiv' => 'Overview'));
$this->load->view('language'); 
	?>

   <h2>Allgemein</h2>
	<p>Wir freuen uns über Ihr Interesse an unseren Weiterbildungsprogrammen.<br><br>
	Bitte klicken Sie auf "Weiteren Studiengang/Lehrgang hinzufügen" um Ihrer Bewerbung einen Studiengang oder Lehrgang hinzuzufügen.
	Gegebenenfalls sind danach weitere Daten zu ergänzen, bevor Sie die Bewerbung im letzten Schritt abschicken können.</p>	
	<br><br>
	<p><b>Aktuelle Bewerbungen:</b></p>
	
		<?php $this->load_views('view_overview'); ?>
	<br>
	<button class="btn-nav btn btn-success" type="button" data-toggle="modal" data-target="#liste-studiengaenge">
		Weiteren Studiengang/Lehrgang hinzufügen	</button>
	<button class="btn-nav btn btn-default" type="button" data-jump-tab="daten">
		Weiter	</button>
	<br/><br/>

<?php 
	$this->load->view('templates/footer');
