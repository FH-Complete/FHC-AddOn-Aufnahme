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
    
    $this->load_views('view_messages'); ?>


    
</div>
<?php
    $this->load->view('templates/footer');
?>