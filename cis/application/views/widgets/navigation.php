<nav class="col-xs-6 col-sm-3 navbar navbar-default navbar-fixed-right">

<ul class="nav nav-pills nav-stacked">
        <?php foreach($items as $item): ?>
        <li><a href="#<?php echo $item; ?>"><?php echo $item; ?></a></li>
        <?php endforeach; ?>
      </ul>

</nav>
