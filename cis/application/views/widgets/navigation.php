<nav class="col-sm-12 navbar navbar-default navbar-fixed-right">

<ul class="nav nav-pills nav-stacked">
        <?php foreach($items as $item): ?>
        <li><a href="<?php echo (isset($href[$item["id"]]) ? $href[$item["id"]] :$item["href"]); ?>"><?php echo $item["name"]; ?></a></li>
        <?php endforeach; ?>
      </ul>

</nav>
