<?php
/**
 * ./cis/application/views/bewerbung/studiengang.php
 *
 * @package default
 */

?>
<a class="<?php echo (isset($studiengang_kz) && ($studiengang_kz == $studiengang->studiengang_kz)) ? "" : "collapsed"?>" data-toggle='collapse' data-target='#<?php echo $studiengang->studiengang_kz; ?>'>
	<h1 class="studiengang-list-item"><?php echo $studiengang->bezeichnung ?>
		(<?php echo $studiengang->studienplan->orgform_kurzbz; ?>)
	</h1>
</a>
<button class="btn btn-sm btn-primary btn-background" type="button" onclick="confirmStorno(<?php echo $studiengang->studiengang_kz; ?>)">
	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
</button>
<span id="infotext_<?php echo $studiengang->studienplan->studienplan_id; ?>" class="infotext" studienplan_id="<?php echo $studiengang->studienplan->studienplan_id; ?>" studiengang_kz="<?php echo $studiengang->studiengang_kz; ?>">
	
</span>
</br>
