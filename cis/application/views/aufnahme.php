<?php $this->lang->load(array('global', 'aufnahme'), $sprache); ?>

<div class="container">
	<?php $this->load->view('language'); ?>

	<ol class="breadcrumb">
		<li class="active">Aufnahme</li>
	</ol>
	<?php $this->load->view('aufnahme/main_menu'); ?>
	<div class="tab-content">
		<?php $this->load->view('aufnahme/status_overview'); ?>
	</div>
</div>

<script type="text/javascript">

	/*window.setTimeout(function() {
		$("#success-alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove(); 
		});
	}, 1500);*/

</script>
