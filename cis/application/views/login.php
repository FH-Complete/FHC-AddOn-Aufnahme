<?php
/**
 * ./cis/application/views/login.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'login'), $sprache);
$this->load->view('templates/metaHeader');
?>

<div class="container">
    <?php $this->load->view('language'); ?>

    <ol class="breadcrumb">
	<li class="active">Login</li>
    </ol>
    <div class="row">
	<!--<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3">-->
	<div class="col-sm-8 col-sm-offset-2">
	    <form method="POST" id="lp" class="form-horizontal">
		<img class="center-block img-responsive" src="<?php echo base_url('themes/' . $this->config->item('theme') . '/images/logo.png'); ?>" />
		<h1 id="login_header" class="text-center page-header"><?php echo $this->getPhrase("Home/Greetings", $sprache, $this->config->item('root_oe')); ?></h1>
		<?php
if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>
		<div class="panel panel-info">
		    <div class="panel-heading text-center">
			<h3 class="panel-title"><?php echo $this->getPhrase("Home/NoAccountOrAccessCode", $sprache, $this->config->item('root_oe')); ?></h3>
		    </div>
		    <div class="panel-body text-center">
			<br>
			<a class="btn btn-primary btn-lg" href="<?php echo base_url($this->config->config["index_page"]."/Registration".(isset($studiengang_kz) ? "?studiengang_kz=".$studiengang_kz : ""))?>" role="button"><?php echo $this->lang->line('login_SubscribeHere'); ?></a>
			<br><br>
		    </div>
		</div>

		<?php $this->load_views('view_login'); ?>
	    </form>
	</div>
    </div>
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
