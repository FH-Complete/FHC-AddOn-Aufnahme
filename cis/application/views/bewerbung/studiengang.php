<?php
/**
 * ./cis/application/views/bewerbung/studiengang.php
 *
 * @package default
 */

?>
<a class="<?php echo (isset($studiengang_kz) && ($studiengang_kz == $studiengang->studiengang_kz)) ? "" : "collapsed"?>" data-toggle='collapse' data-target='#<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>'>
	<h1 class="studiengang-list-item"><?php echo $studiengang->bezeichnung ?>
		(<?php echo $studiengang->studienplaene[0]->orgform_kurzbz; ?>)
	</h1>
</a>
<?php
if(!((isset($bewerbung_abgeschickt))
    && ($bewerbung_abgeschickt === true)
    && (isset($abgeschickt_array))
    && (isset($abgeschickt_array[$studiengang->studiengang_kz]))
    && ($abgeschickt_array[$studiengang->studiengang_kz] == true)))
{
    ?>
    <button class="btn btn-sm btn-primary btn-background" type="button" onclick="confirmStorno(<?php echo $studiengang->studiengang_kz; ?>, <?php echo $studiengang->studienplaene[0]->studienplan_id; ?>)">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
    </button>
<?php
}
 ?>

<span id="infotext_<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" class="infotext" studienplan_id="<?php echo $studiengang->studienplaene[0]->studienplan_id; ?>" studiengang_kz="<?php echo $studiengang->studiengang_kz; ?>">
	
</span>
</br>
