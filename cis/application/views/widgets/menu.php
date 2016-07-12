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
                    <li class="<?php if (isset($data['aktiv']) && $data["aktiv"] === $item["name"]) echo 'active'; ?>">
                        <a href="<?php echo $item['href']; ?>">
                            <?php echo $item['name']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
	    <?php $this->load->view('language'); ?>
        </div>
    </div>
</nav>
