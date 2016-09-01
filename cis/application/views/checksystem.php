<?php
/**
 * ./cis/application/views/checksystem.php
 *
 * @package default
 */


$this->lang->load(array('aufnahme')); ?>

<div class="container">
	<ol>
		<li>ENVIRONMENT: <?php echo ENVIRONMENT; ?></li>
		<li>API-Test:
			<?php
if ($apitest->error)
	echo '<font color="red">Error (Time: '.$apitest->time.'ms): '.$apitest->value.'</font>';
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
?>
				</li>
				<li>
					<?php
echo 'Controller Studiengaenge/index(): '.$studiengaenge->time.'ms';
if (isset($studiengaenge->time1))
	echo '<br>'.$studiengaenge->time1;
if (isset($studiengaenge->msg))
	echo '<font color="red"><br>'.$studiengaenge->msg.'</font>';
?>
				</li>

			</ul>
		</li>
	</ol>

</div>
