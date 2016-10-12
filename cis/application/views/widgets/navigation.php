<?php
/**
 * ./cis/application/views/widgets/navigation.php
 *
 * @package default
 */


?>
<nav class="col-sm-12 navbar navbar-default navbar-fixed-right">
	<ul class="nav nav-pills nav-stacked">
		<?php foreach ($items as $item): ?>
			<li class="<?php if (isset($aktiv) && ($aktiv === $item["id"])) echo "active"; ?> <?php echo $item["id"]?> nav-row">
				<a href="<?php echo isset($href[$item["id"]]) ? $href[$item["id"]] :$item["href"]; ?>">
				<?php echo $item["name"]; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
