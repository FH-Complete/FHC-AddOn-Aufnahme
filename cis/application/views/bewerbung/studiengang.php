<?php
/**
 * ./cis/application/views/bewerbung/studiengang.php
 *
 * @package default
 */


?>
<a class="collapsed" data-toggle='collapse' data-target='#<?php echo $studiengang->studiengang_kz; ?>'>
	<h1 class="studiengang-list-item"><?php echo $studiengang->bezeichnung ?>
		(<?php echo $studiengang->orgform_kurzbz; ?>)
	</h1>
</a>
<a href="<?php echo base_url($this->config->config["index_page"]."/Bewerbung/storno/$studiengang->studiengang_kz") ?>" onclick="confirmStorno(<?php echo $studiengang->studiengang_kz; ?>)">
	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
</a>
</br>
