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
                <?php foreach ($items as $item): ?>
                    <li>
                        <a href="<?php echo $item['href']; ?>">
                            <?php echo $item['name']; ?>
                            <?php if (isset($item['glyphicon'])) echo ' <span class="glyphicon ' . $item['glyphicon'] . '"></span>'; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
	    <?php $this->load->view('language'); ?>
        </div>
    </div>
</nav>
