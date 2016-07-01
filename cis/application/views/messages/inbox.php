<div>
    <h1><?php echo $title; ?></h1>
    <a href="<?php echo base_url($this->config->config["index_page"].'/Messages/newMessage'); ?>">
	<a href="<?php echo base_url($this->config->config["index_page"]."/Messages/newMessage"); ?>">
	    <button class="answer">
		<?php echo $this->lang->line('msg_newMessage'); ?>
	    </button>
	</a>
    </a>
    <?php if($view=="messages") { ?>
	<div id="messages">
	    <table width="100%" border="1">
		<thead>
		    <tr>
			<th><?php echo $this->lang->line('msg_sender'); ?></th>
			<th><?php echo $this->lang->line('msg_subject'); ?></th>
			<th><?php echo $this->lang->line('msg_date'); ?></th>
			<th>&nbsp;</th>
		    </tr>
		</thead>
		    <tbody>
			<?php foreach($messages as $msg) {
			    
			    ?>
			<tr class="message" id="<?php echo $msg->message_id; ?>">
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
			    <td>
				<a href="<?php echo base_url($this->config->config["index_page"].'/Messages/viewMessage/'.$msg->message_id);?>"><?php echo $this->lang->line('msg_view'); ?></a>
			    </td>
			</tr>
			<?php } ?>
		    </tbody>
	    </table>
	</div>
    <?php } ?>
</div>

