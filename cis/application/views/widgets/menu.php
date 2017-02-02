<?php
/**
 * ./cis/application/views/widgets/menu.php
 *
 * @package default
 */


?>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bewerber-navigation" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bewerber-navigation">
            <ul class="nav navbar-nav">

                <?php
					foreach ($items as $item): ?>
                    <li class="<?php if (isset($data['aktiv']) && $data["aktiv"] === $item["id"]) echo 'active'; ?>">
                        <a href="<?php echo $item['href']; ?>">
                            <?php
                                $sprache = $this->session->{'Sprache.getSprache'}->retval;
								echo $item['name'][$sprache->index-1]." ";
								if(isset($data["numberOfUnreadMessages"]) && ($data["numberOfUnreadMessages"]->unreadmessages > 0) &&($item["id"] == "Nachrichten"))
								{
									echo "<span class='glyphicon glyphicon-exclamation-sign red'></span>";	
								}
								if($item["id"] == "Dokumente")
								{
									echo "<span id='document-sign' class='glyphicon glyphicon-exclamation-sign red' style='display: none;'></span>";	
								}
							?>
                        </a>
						
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			url: '<?php echo base_url($this->config->config["index_page"]."/Dokumente/areDocumentsComplete"); ?>',
			type: 'POST',
			cache: false,
			dataType: 'json',
			success: function(data, textStatus, jqXHR)
			{
				if((data.complete !== undefined) && (data.complete == false))
				{
					$("#document-sign").show();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
				// STOP LOADING SPINNER
			}
		});
	});
</script>
