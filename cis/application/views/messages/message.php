<div id="message_<?php echo $msg->message_id; ?>" class="message">
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