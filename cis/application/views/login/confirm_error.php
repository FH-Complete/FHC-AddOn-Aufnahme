<?php
/**
 * ./cis/application/views/login/confirm_error.php
 *
 * @package default
 */

$this->load->view('templates/header');
$this->load->view('templates/metaHeader');
$this->lang->load(array('aufnahme', 'login', 'registration'), $sprache); ?>

<div class="container">
    <?php $this->load->view('templates/iconHeader', array("header" => $this->getPhrase("Registration/HeaderConfirmation", $sprache))); ?>
	<div class="row">
		<div id="confirm">
			<div class="row">
				<div class="col-sm-12">
					<?php
						echo (isset($message)) ? $message : "";
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footer');
