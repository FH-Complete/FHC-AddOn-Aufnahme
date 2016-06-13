<div role="tabpanel" class="tab-pane" id="send">
    <h1><?php echo $this->lang->line("send_header"); ?></h1>
    <fieldset><?php echo $this->lang->line("send_einleitung").'!'; ?></fieldset>
    <fieldset><?php echo $this->lang->line("send_text"); ?></fieldset>
    <?php echo $studiengang->bezeichnung; ?></br>
    <?php echo form_open("Send/send/".$studiengang->studiengang_kz."/".$studiengang->studienplan->studienplan_id, array("id" => "PersonForm", "name" => "PersonForm")); ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo form_submit(array("value"=>"Daten absenden", "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
                </div>
            </div>
            </div>
        </div>
    <?php echo form_close(); ?>
    
</div>
