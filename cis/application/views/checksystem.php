<?php $this->lang->load(array('aufnahme')); ?>

<div class="container">
	<ol>
		<li>ENVIRONMENT: <?php echo ENVIRONMENT; ?></li>
		<li>API-Test: 
			<?php 
				if ($apitest->error)
					echo 'Error (Time: '.$apitest->time.'ms): '.$apitest->value;
				else
					echo 'OK (Time elapsed: '.$apitest->time.'ms)';
			?>
		</li>
		<li>Performance-Test: 
			<?php 
				if ($apitest->error)
					echo 'Error (Time: '.$apitest->time.'ms): '.$apitest->value;
				else
					echo 'OK (Time elapsed: '.$apitest->time.'ms)';
			?>
		</li>
	</ol>
	
</div>
