<?php
$this->load->view('templates/header');
//$this->load->view('menu', 'person');
$this->lang->load(array('aufnahme', 'messages'), $language);
?>

<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Nachrichten'));
    if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
    ?>



    <?php 
    switch($view)
    {
	case "newMessage":
	    $this->load->view('messages/newMessage');
	    break;
	case "message":
	    $this->load->view("messages/message");
	    break;
	default:
	    $this->load->view('messages/inbox');
	    break;
    }
    
    ?>


    
</div>
<?php
    $this->load->view('templates/footer');
?>