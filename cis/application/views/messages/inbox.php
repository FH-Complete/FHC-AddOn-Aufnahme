<?php
/**
 * ./cis/application/views/messages/inbox.php
 *
 * @package default
 */


?>
<div>
    <h1 class="message_title"><?php echo $this->lang->line("msg_inbox"); ?></h1>
    <a href="<?php echo base_url($this->config->config["index_page"].'/Messages/newMessage'); ?>">
	<button class="answer btn btn-sm btn-primary">
	    <?php echo $this->lang->line('msg_newMessage'); ?>
	</button>
    </a>
    <!-- TODO write JS function with API call to set message to status read -->
    <?php if ($view=="messages") { ?>
	<div id="messages">
	    <?php foreach ($messages as $msg) { ?>
	    <div id="message_<?php echo $msg->message_id; ?>" class="row message-item">
			<div class="col-sm-1">
				<span id="status_<?php echo $msg->message_id;?>" class='<?php echo ($msg->status == MSG_STATUS_READ) ? "icon-read" : (($msg->status == MSG_STATUS_UNREAD) ? "icon-unread" : "") ;?>'>&nbsp;</span>
			</div>
			<div class="col-sm-5">
				<a class="collapsed" messageId="<?php echo $msg->message_id; ?>" data-toggle='collapse' data-target='#message_body_<?php echo $msg->message_id; ?>'>
				<?php echo $msg->subject; ?>
				</a>
			</div>
			<div class="col-sm-2">
				<?php
					$time = strtotime($msg->insertamum);
					echo date("d.m.Y H:m", $time);
				?>
			</div>
			<div class="col-sm-1">
				<span class="icon-paperclip"></span>
			</div>
			<div class="col-sm-2">
				<a href="<?php echo base_url($this->config->config['index_page'].'/Messages/answerMessage/'.$msg->message_id.'/'.$msg->oe_kurzbz); ?>">
				<button class="answer btn btn-sm btn-primary" id="answer_<?php echo $msg->message_id; ?>">
					<?php echo $this->lang->line('msg_answer'); ?>
				</button>
				</a>
			</div>
			<div class="col-sm-1">
				<a href="<?php echo base_url($this->config->config['index_page'].'/Messages/deleteMessage/'.$msg->message_id); ?>">
				<button class="btn btn-sm btn-primary icon-trash" id="answer_<?php echo $msg->message_id; ?>"><?php echo $this->lang->line('msg_delete'); ?></button>
				</a>
			</div>
			<div id="message_body_<?php echo $msg->message_id; ?>" class="collapse messageBody" style="float: left;">
				<?php echo $msg->body;?>
			</div>
	    </div>
	    
	    <?php } ?>
	</div>
    <?php } ?>
</div>
