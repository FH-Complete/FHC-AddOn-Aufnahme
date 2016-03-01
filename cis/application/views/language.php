<div class="dropdown pull-right">
	<button class="btn btn-default dropdown-toggle" type="button" id="sprache-label" data-toggle="dropdown" aria-expanded="true">
		<?php echo $this->lang->line($sprache); ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu" role="menu" aria-labelledby="sprache-label" id="sprache-dropdown">
		<li role="presentation">
			<a href="#" role="menuitem" tabindex="-1" data-sprache="english"><?php echo $this->lang->line('english'); ?></a>
		</li>
		<li role="presentation">
			<a href="#" role="menuitem" tabindex="-1" data-sprache="german"><?php echo $this->lang->line('german'); ?></a>
		</li>
	</ul>
	
	<script type="text/javascript">
		function changeSprache(sprache)
		{
			var method = '';
			window.location.href = "?sprache=" + sprache + "&stg_kz=<?php echo $stg_kz;?>";
		}
		
		$(function() {
			$('#sprache-dropdown a').on('click', function() {
				var sprache = $(this).attr('data-sprache');
				changeSprache(sprache);
			});
		});		
	</script>
</div>
	
