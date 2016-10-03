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
								echo $item['name']." "; 
								if(isset($data["numberOfUnreadMessages"]) && ($data["numberOfUnreadMessages"] > 0) &&($item["id"] == "Nachrichten"))
								{
									echo "<span class='glyphicon glyphicon-exclamation-sign red'></span>";	
								}
							?>
                        </a>
						
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
