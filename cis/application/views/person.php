<?php 
	$this->load->view('templates/header');
	$this->lang->load(array('aufnahme', 'person'), $language);
    // This is an example to show that you can load stuff from inside the template file
    echo $this->template->widget("menu", array('aktiv' => 'Person'));
	
?>

<div class="container">
	<div class="col-xs-12 col-sm-9">
		<?php echo $this->template->widget("person_nav", array('aktiv' => 'Person')); ?>
		<?php $this->load_views('view_person'); ?>
	</div>
</div>


<?php 
	$this->load->view('templates/footer');
