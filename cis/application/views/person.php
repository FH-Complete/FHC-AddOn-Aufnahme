<?php 
	$this->load->view('templates/header');
	//$this->load->view('menu', 'person');
	$this->lang->load(array('aufnahme', 'person'), $sprache);
    // This is an example to show that you can load stuff from inside the template file
    echo $this->template->widget("menu", array('aktive' => 'Allgemein'));
?>

<!--
<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Login</li>
    </ol>
    <div class="row">
	<div class="col-sm-8 col-sm-offset-2">
	    <form method="POST" id="lp" class="form-horizontal">
		<img class="center-block img-responsive" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>" />
		<h1 class="text-center page-header"><?php echo $this->lang->line('login_Welcome'); ?></h1>
		<div class="panel panel-info">
		    <div class="panel-heading text-center">
			<h3 class="panel-title"><?php echo $this->lang->line('login_NoAccount'); ?></h3>
		    </div>
		    <div class="panel-body text-center">
			<br>
			<a class="btn btn-primary btn-lg" href="<?php echo base_url("index.dist.php/Registration")?>" role="button"><?php echo $this->lang->line('login_SubscribeHere'); ?></a>
			<br><br>
		    </div>
		</div>

	    </form>
	</div>
    </div>
</div>
-->
<div class="container">
	<?php $this->load_views('view_person'); ?>
</div>

<script type="text/javascript">

	/*window.setTimeout(function() {
		 $("#success-alert").fadeTo(500, 0).slideUp(500, function(){
		 $(this).remove(); 
		 });
		 }, 1500);*/

</script>

<?php 
	$this->load->view('templates/footer');
