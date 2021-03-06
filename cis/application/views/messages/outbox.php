<?php
/**
 * ./cis/application/views/messages/outbox.php
 *
 * @package default
 */

?>
    <div>
        <h1 class="message_title"><?php echo $this->lang->line("msg_outbox"); ?></h1>
        <!-- TODO write JS function with API call to set message to status read -->
        <?php if ($view == "messages")
        { ?>
            <div id="messages_outbox">
                <?php foreach ($messages_outbox as $msg)
                {
                    if ($msg->status <= MSG_STATUS_READ)
                    {
                        ?>
                        <div id="message_<?php echo $msg->message_id; ?>" class="row message-item">
                            <div class="col-sm-8" style="display: none;">
                            <span id="status_<?php echo $msg->message_id; ?>"
                                  class='<?php echo ($msg->status == MSG_STATUS_READ) ? "icon-read" : (($msg->status == MSG_STATUS_UNREAD) ? "icon-unread" : ""); ?>'>&nbsp;</span>
                            </div>
                            <div class="col-sm-8 message-title">
                                <a class="collapsed" messageId="<?php echo $msg->message_id; ?>" data-toggle='collapse'
                                   data-target='#message_body_outbox_<?php echo $msg->message_id; ?>'>
                                    <?php echo $msg->subject; ?>
                                </a>
                            </div>
                            <div class="col-sm-4 text-right message-meta">
                                <date>
                                    <?php
                                    $time = strtotime($msg->insertamum);
                                    echo date("d.m.Y H:i", $time);
                                    ?>
                                </date>
                                <a href=""><span class="glyphicon glyphicon-paperclip"
                                                 style="visibility: hidden;"></span></a>
                                <!--				<a href="<?php echo base_url($this->config->config['index_page'] . '/Messages/answerMessage/' . $msg->message_id . '/' . $msg->oe_kurzbz); ?>">
					<button class="answer btn btn-sm btn-primary" id="answer_<?php echo $msg->message_id; ?>">
						<?php echo $this->lang->line('msg_answer'); ?>
					</button>
				</a>-->
                                <a href="<?php echo base_url($this->config->config['index_page'] . '/Messages/deleteSentMessage/' . $msg->message_id); ?>">
                                    <button class="btn btn-sm btn-primary"
                                            id="delete_<?php echo $msg->message_id; ?>"><span
                                                class="glyphicon glyphicon-trash"></span></button>
                                </a>
                            </div>
                            <div id="message_body_outbox_<?php echo $msg->message_id; ?>" class="collapse message-body"
                                 style="float: left;">
                                <?php echo $msg->body; ?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
            </div>
        <?php } ?>
    </div>
<?php

