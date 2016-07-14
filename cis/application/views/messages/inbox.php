<div>
    <h1 class="message_title"><?php echo $this->lang->line("msg_inbox"); ?></h1>
    <a href="<?php echo base_url($this->config->config["index_page"].'/Messages/newMessage'); ?>">
	<button class="answer btn btn-sm">
	    <?php echo $this->lang->line('msg_newMessage'); ?>
	</button>
    </a>
    <!-- write JS function with API call to set message to status read -->
    <?php if($view=="messages") { ?>
	<div id="messages">
	    <?php foreach($messages as $msg) { ?>
	    <div id="message_<?php echo $msg->message_id; ?>">
		<div style="float: left; width: 5%;">
		    <span class='<?php echo ($msg->status == MSG_STATUS_READ) ? "icon-read" : ($msg->status == MSG_STATUS_UNREAD) ? "icon-unread" : "" ;?>'></span>
		</div>
		<div style="float: left; width: 50%;">
		    <a class="collapsed" data-toggle='collapse' data-target='#message_body_<?php echo $msg->message_id; ?>'>
			<?php echo $msg->subject; ?>
		    </a>
		</div>
		<div style="float: left; width: 15%;">
		    <?php
			$time = strtotime($msg->insertamum);
			echo date("d.m.Y h:m", $time); 
		    ?>
		</div>
		<div style="float: left; width: 10%;"><span class="icon-paperclip"></span></div>
		<div style="float: left; width: 10%;">
		    <a href="<?php echo base_url($this->config->config['index_page'].'/Messages/answerMessage/'.$msg->message_id.'/'.$msg->oe_kurzbz); ?>">
			<button class="answer btn btn-sm" id="answer_<?php echo $msg->message_id; ?>">
			    <?php echo $this->lang->line('msg_answer'); ?>
			</button>
		    </a>
		</div>
		<div style="float: left; width: 10%;">
		    <a href="<?php echo base_url($this->config->config['index_page'].'/Messages/deleteMessage/'.$msg->message_id); ?>">
			<button class="btn btn-sm icon-trash" id="answer_<?php echo $msg->message_id; ?>"></button>
		    </a>
		</div>
	    </div>
	    <div id="message_body_<?php echo $msg->message_id; ?>" class="collapse" style="float: left;">
		<?php echo $msg->body;?>
	    </div>
	    <?php } ?>
	</div>
    <?php } ?>
</div>

