<?php
$this->load->view('templates/header');
$this->lang->load(array('aufnahme', 'requirements'), $language);

if (isset($error) && ($error->error === true))
    echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

<div class="container">
    <?php
    $this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
    echo $this->template->widget("menu", array('aktiv' => 'Bewerbung'));
    ?>
    <div class="row">
        <div class="col-sm-12">
            <?php $this->load_views('view_bewerbung_studiengang'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 navigation">
            <?php echo 
                $this->template->widget(
                    "person_nav",
                    array(
                        'aktiv' => 'requirements',
                        "href"=>array(
                            "send"=>site_url("/Send?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
                            "summary"=>site_url("/Summary?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
                            "requirements"=>site_url("/Requirements?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id),
                            "personalData"=>site_url("/Bewerbung?studiengang_kz=".$studiengang->studiengang_kz."&studienplan_id=".$studiengang->studienplan->studienplan_id)
                        )
                    )
                ); ?>
        </div>
        <div class="col-sm-8">
            <div role="tabpanel" class="tab-pane" id="requirements">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $this->getPhrase("ZGV/AdmissionRequirements", $sprache); ?>
                    </div>
                </div>
		<?php $this->load_views('view_requirements'); ?>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo form_submit(array("value"=>"Speichern", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
                    </div>
                </div>
	    </div>
	</div>
	<?php echo form_close(); ?>
    </div>
</div>

<?php
$this->load->view('templates/footer');
