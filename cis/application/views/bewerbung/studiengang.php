<?php
/**
 * ./cis/application/views/bewerbung/studiengang.php
 *
 * @package default
 */


?>
<a class="collapsed" data-toggle='collapse' data-target='#<?php echo $studiengang->studiengang_kz; ?>'><h1 class="studiengang-list-item"><?php echo $studiengang->bezeichnung ?> (<?php echo $studiengang->orgform_kurzbz; ?>)</h1></a><a href="" onclick="confirmStorno()"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></br>
