<?php
/**
 * ./cis/application/views/downloads.php
 *
 * @package default
 */


$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'downloads'), $sprache);
$this->load->view('templates/cookieHeader');
$this->load->view('templates/metaHeader');
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Downloads', 'numberOfUnreadMessages'=>$numberOfUnreadMessages, 'studiengaenge' => $studiengaenge));
if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

</div>
<?php
$this->load->view('templates/footer');
?>
