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
		<li>Performance-Tests: 
			<ul>
				<li>
					<?php 
						echo 'Constructor: '.$constructorTime.'ms';
					?>
				</li>
				<li>
					<?php 
						echo 'View "templates/header": '.$perfViewHeader->time.'ms';
						echo FCPATH.'../vendor/';
					?>
				</li>
			</ul>
		</li>
	</ol>
	
</div>
