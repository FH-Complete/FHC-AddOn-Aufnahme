<?php
$this->load->view('templates/header');
//$this->load->view('menu', 'person');
$this->lang->load(array('aufnahme', 'messages'), $language);
?>


<div class="container">
    <?php
    echo $this->template->widget("menu", array('aktiv' => 'Overview'));
    $this->load->view('language');
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