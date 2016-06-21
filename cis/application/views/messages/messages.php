<div>
    <h1><?php echo $title; ?></h1>
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
		    <tr class="message" id="1">
			<td></td>
			<td>Studiengang XY</td>
			<td>Ihre Bewerbung</td>
			<td>21.06.2016</td>
		    </tr>
		    <tr class="message" id="2">
			<td>!</td>
			<td>Studiengang XY</td>
			<td>Ihre Bewerbung</td>
			<td>20.06.2016</td>
		    </tr>
		</tbody>
	</table>
    </div>
    <div id="message_1" style="display: none;" class="message">
	<div id="buttons"><button><?php echo $this->lang->line('msg_answer'); ?></button></div>
	<div style="width: 100%; height: 400px; border: 1px solid black;">
	    Message 1
	    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
	</div>
    </div>
    <div id="message_2" style="display: none;" class="message">
	<div id="buttons"><button><?php echo $this->lang->line('msg_answer'); ?></button></div>
	<div style="width: 100%; height: 400px; border: 1px solid black;">
	    Message 2
	    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
	</div>
    </div>
    
    <script type="text/javascript">
	$(document).ready(function(){
	   $(".message").click(function(event){
	       console.log(event); 
	       var id = $(event.currentTarget).attr("id");
	       console.log(id);
	       $("tr.message").css("background-color", "white");
	       $("#"+id).css("background-color", "grey");
	       $("div.message").hide();
	       $("#message_"+id).show();
	   });
	});
    </script>
</div>

