<legend><?php echo $this->lang->line("summary_requirements_header"); ?></legend>
<div class="row">
    <div class="col-sm-12">
	<div class="col-sm-6">
	    <?php echo $this->lang->line("summary_Abschlusszeugnis"); ?>
	</div>
	<div class="col-sm-5 <?php echo (!isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]])) ? "incomplete" : ""; ?>">
	    <div class="form-group">
		<?php if(!isset($dokumente[$this->config->item('dokumentTypen')["abschlusszeugnis"]])) {
		    echo $this->lang->line('summary_unvollstaendig');
		 }
		 else
		 {
		     echo $this->lang->line('summary_dokumentVorhanden');
		 }
		 ?>
	    </div>
	</div>
    </div>
</div>