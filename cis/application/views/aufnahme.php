<?php
/**
 * ./cis/application/views/aufnahme.php
 *
 * @package default
 */


$this->lang->load(array('aufnahme'), $sprache); ?>

<div class="container">
	<?php //$this->load->view('language'); ?>

	<ol class="breadcrumb">
		<li class="active">Aufnahme</li>
	</ol>
	<?php $this->load->view('aufnahme/main_menu'); ?>
	<div class="tab-content">
		<?php // $this->load->view('aufnahme/status_overview');

$found = false;
foreach ($tabs as $t) {
	if ($tab == $t["id"]) {
		$found = true;
		break;
	}
}

if ($found) {
	$this->load->view('aufnahme/'.$t["id"]);
}
else {
	$this->load->view('aufnahme/'.$tabs[0]["id"]);
}
//   switch($tab)
//   {
//    case 'studiengaenge':
//
//     break;
//    case 'termine':
//     $this->load->view('aufnahme/termine');
//     break;
//    default:
//     $this->load->view('aufnahme/studiengaenge');
//   }


?>
	</div>
</div>
