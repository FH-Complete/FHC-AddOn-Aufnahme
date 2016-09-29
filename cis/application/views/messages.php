<?php
/**
 * ./cis/application/views/messages.php
 *
 * @package default
 */


$this->load->view('templates/header');
//$this->load->view('menu', 'person');
$this->lang->load(array('aufnahme', 'messages'), $sprache);
$this->load->view('templates/metaHeader');
?>

<div class="container">
    <?php
$this->load->view('templates/iconHeader', array("name"=>$person->vorname." ".$person->nachname));
echo $this->template->widget("menu", array('aktiv' => 'Nachrichten'));
if (isset($error) && ($error->error === true))
	echo '<div class="alert alert-danger" role="alert">'.$error->msg.'</div>';
?>

    <?php
switch ($view) {
case "newMessage":
	$this->load->view('messages/newMessage');
	break;
case "message":
	$this->load->view("messages/message");
	break;
default:
	$this->load->view('messages/inbox');
	$this->load->view('messages/outbox');
	break;
}

?>

</div>
<?php
$this->load->view('templates/footer');
?>

<script type="text/javascript">
    $(document).ready(function(){
		$(".collapsed").on("click", function(event){
			var messageId = $(event.target).attr("messageId");

			if($("#status_"+messageId).hasClass("icon-unread"))
			{

			$.ajax({
				method: "POST",
				dataType: 'json',
				url: '<?php echo base_url($this->config->config["index_page"]."/Messages/changeMessageStatus"); ?>',
				data: {
					message_id: messageId,
					status: "<?php echo MSG_STATUS_READ; ?>"
				}
			}).done(function(data){
				if(data.error === 0)
				{
					$("#status_"+messageId).removeClass("icon-unread");
					$("#status_"+messageId).addClass("icon-read");
				}
			});
			}
		});
    });
</script>
