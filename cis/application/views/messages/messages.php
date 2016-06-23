<div>
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo base_url($this->config->config["index_page"].'/Messages/newMessage'); ?>">
	<button class="answer">
	    <?php echo $this->lang->line('msg_newMessage'); ?>
	</button>
    </a>
    <?php if($view=="messages") { ?>
	<div id="messages">
	    <table width="100%" border="1">
		<thead>
		    <tr>
			<th><?php echo $this->lang->line('msg_priority'); ?></th>
			<th><?php echo $this->lang->line('msg_sender'); ?></th>
			<th><?php echo $this->lang->line('msg_subject'); ?></th>
			<th><?php echo $this->lang->line('msg_date'); ?></th>
		    </tr>
		</thead>
		    <tbody>
			<?php foreach($messages as $msg) { ?>
			<tr class="message" id="<?php echo $msg->message_id; ?>">
			    <td>
				<?php echo $msg->priority; ?>
			    </td>
			    <td>
				<?php echo $msg->oe_kurzbz; ?>
			    </td>
			    <td>
				<?php echo $msg->subject; ?>
			    </td>
			    <td>
				<?php
				$time = strtotime($msg->insertamum);
				echo date("d.m.Y h:m", $time); ?>
			    </td>
			</tr>
			<?php } ?>
		    </tbody>
	    </table>
	</div>
	<?php foreach($messages as $msg) { ?>
	    <div id="message_<?php echo $msg->message_id; ?>" style="display: none;" class="message">
		<div class="buttons">
		    <a href="<?php echo base_url($this->config->config["index_page"].'/Messages/answerMessage/'.$msg->message_id."/".$msg->oe_kurzbz); ?>">
			<button class="answer" id="answer_<?php echo $msg->message_id; ?>">
			    <?php echo $this->lang->line('msg_answer'); ?>
			</button>
		    </a>
		</div>
		<div style="width: 100%; height: 400px; border: 1px solid black;">
		    <?php echo $msg->body; ?>
		</div>
	    </div>
	<?php } ?>
    <?php } ?>
    <?php if($view=="newMessage") { ?>
	<div id="answer">
	    <?php echo form_open("Messages/sendMessage", array("id" => "MessageForm", "name" => "MessageForm")); ?>
		<?php echo form_input(array('id' => 'msg_relationMessage_id', 'name' => 'msg_relationMessage_id', "type" => "hidden", "value" => isset($message_id) ? $message_id : null, "class" => "form-control")); ?>
		<div class="row">
		    <div class="col-sm-6">
			<div class="form-group <?php echo (form_error("msg_subject") != "") ? 'has-error' : '' ?>">
			    <?php echo form_label($this->lang->line('msg_subject'), "msg_subject", array("name" => "msg_subject", "for" => "msg_subject", "class" => "control-label")) ?>
			    <?php echo form_input(array('id' => 'msg_subject', 'name' => 'msg_subject', "type" => "text", "value" => "", "class" => "form-control")); ?>
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
		<?php echo form_submit(array("value"=>$this->lang->line('msg_send'), "name"=>"submit_btn", "class"=>"btn btn-primary")); ?>
	    <?php echo form_close(); ?>
	</div>
    <?php } ?>
    <script type="text/javascript">
	$(document).ready(function(){
	   $("tr.message").click(function(event){
	       var id = $(event.currentTarget).attr("id");
	       $("tr.message").css("background-color", "white");
	       $("#"+id).css("background-color", "grey");
	       $("div.message").hide();
	       $("#answer").hide();
	       $("#message_"+id).show();
	   });
	});
    </script>
</div>

