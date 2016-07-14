<div id="answer">
    <?php echo form_open("Messages/sendMessage", array("id" => "MessageForm", "name" => "MessageForm")); ?>
	<?php echo form_input(array('id' => 'msg_relationMessage_id', 'name' => 'msg_relationMessage_id', "type" => "hidden", "value" => isset($message_id) ? $message_id : null, "class" => "form-control")); ?>
	<div class="row">
	    <div class="col-sm-6">
		<div class="form-group <?php echo (form_error("msg_subject") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('msg_subject'), "msg_subject", array("name" => "msg_subject", "for" => "msg_subject", "class" => "control-label")) ?>
		    <?php echo form_input(array('id' => 'msg_subject', 'name' => 'msg_subject', "type" => "text", "value" =>set_value("msg_subject", isset($msg->subject) ? $msg->subject : "") , "class" => "form-control")); ?>
		    <?php echo form_error("msg_subject"); ?>
		</div>
	    </div>   
	</div>
	<div class="row">
	    <div class="col-sm-6">
		<div class="form-group <?php echo (form_error("msg_oe_kurzbz") != "") ? 'has-error' : '' ?>">
		    <?php echo form_label($this->lang->line('msg_oe_kurzbz'), "msg_oe_kurzbz", array("name" => "msg_oe_kurzbz", "for" => "msg_oe_kurzbz", "class" => "control-label")) ?>
		    <?php echo form_dropdown("msg_oe_kurzbz", $studiengaenge, (isset($oe_kurzbz)) ? $oe_kurzbz : null , array('id' => 'msg_oe_kurzbz', 'name' => 'msg_oe_kurzbz', "class" => "form-control")); ?>
		    <?php echo form_error("msg_oe_kurzbz"); ?>
		</div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-12">
		<div class="form-group <?php echo (form_error("msg_body") != "") ? 'has-error' : '' ?>">
		    <?php echo form_textarea(array("name"=>"msg_body", "id"=>"msg_body", "rows"=>"20", "cols"=>"200", "class"=>"form-control")); ?>
		    <?php echo form_error("msg_body"); ?>
		</div>
	    </div>
	</div>
	<?php echo form_submit(array("value"=>$this->lang->line('msg_send'), "name"=>"submit_btn", "class"=>"btn btn-primary icon-absenden")); ?>
    <?php echo form_close(); ?>
</div>