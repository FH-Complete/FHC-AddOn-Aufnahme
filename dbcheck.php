<?php
/**
 * ./dbcheck.php
 *
 * @package default
 */


/* Copyright (C) 2013 FH Technikum-Wien
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

/**
 * FH-Complete Addon Aufnahme Datenbank Check
 *
 * Prueft und aktualisiert die Datenbank
 */
require_once '../../config/system.config.inc.php';
require_once '../../include/basis_db.class.php';
require_once '../../include/functions.inc.php';
require_once '../../include/benutzerberechtigung.class.php';

// Datenbank Verbindung
$db = new basis_db();

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="../../skin/fhcomplete.css" type="text/css">
	<link rel="stylesheet" href="../../skin/vilesci.css" type="text/css">
	<title>Addon Aufnahme Datenbank Check</title>
</head>
<body>
<h1>Addon Aufnahme Datenbank Check</h1>';

$uid = get_uid();
$rechte = new benutzerberechtigung();
$rechte->getBerechtigungen($uid);
$app = 'aufnahme';

if (!$rechte->isBerechtigt('basis/addon', null, 'suid')) {
	exit('Sie haben keine Berechtigung für die Verwaltung von Addons');
}

echo '<input type="button" onclick="window.location.href=\'dbcheck.php?start\'" value="Aktualisierung starten"><br/><br/>';

if (!isset($_GET['start']))
	exit;

echo '<h2>Aktualisierung der Datenbank</h2>';

if($result = $db->db_query("SELECT 1 FROM system.tbl_app WHERE app=".$db->db_add_param($app)))
{
	if($db->db_num_rows($result)==0)
	{
		if($db->db_query("INSERT INTO system.tbl_app(app) VALUES(".$db->db_add_param($app).");"))
			echo 'Neue APP in system.tbl_app hinzugefügt';
		else
			echo 'Fehler beim Anlegen der APP in system.tbl_app';
	}
}

// Textphrasen holen
require_once 'textphrasen.php';

foreach ($textphrasen as $tp) {
	// Check if Phrase exists
	$qry = "SELECT phrase_id FROM system.tbl_phrase WHERE app='$app' AND phrase='".$tp['phrase']."';";
	if (! $resPhrase = $db->db_fetch_object($db->db_query($qry))) {
		// INSERT und id merken
		$qry = "BEGIN;INSERT INTO system.tbl_phrase (app, phrase) VALUES ('$app', '".$tp['phrase']."');";

		if (! $db->db_query($qry))
			echo '<strong>system.tbl_phrase: '.$db->db_last_error().'</strong><br>';
		else {
			//Sequence auslesen
			$qry = "SELECT currval('system.tbl_phrase_phrase_id_seq') as id";
			if ($db->db_query($qry))
			{
				if($row = $db->db_fetch_object())
				{
					// ID holen
					$phrase_id = $row->id;
					$db->db_query('COMMIT');
					echo '<br>Phrase '.$tp['phrase'].' angelegt!';
				}
				else
				{
					$db->db_query('ROLLBACK');
					echo '<strong>system.tbl_phrase: Fehler beim Auslesen der Sequence</strong><br>';
				}
			}
			else
			{
				$db->db_query('ROLLBACK');
				echo '<strong>system.tbl_phrase: Fehler beim Auslesen der Sequence</strong><br>';
			}
		}
	}
	else
		// ID holen
		$phrase_id = $resPhrase->phrase_id;

	// Check if Phrasentext in this language exists
	$qry = "SELECT phrasentext_id FROM system.tbl_phrasentext WHERE phrase_id=$phrase_id AND sprache='".$tp['phrasentext']['sprache']."'";
	if(! $resPhrasentext = $db->db_fetch_object($db->db_query($qry)))
	{
		// INSERT
		$qry = "INSERT INTO system.tbl_phrasentext (phrase_id, sprache, text, description) "
			. "VALUES ($phrase_id, '".$tp['phrasentext']['sprache']."', '".$tp['phrasentext']['text']."', '".$tp['phrasentext']['description']."');";

		if(! $db->db_query($qry))
			echo '<strong>system.tbl_phrasentext: '.$db->db_last_error().'</strong><br>';
		else
		{
			echo '<br>Phrasentext für Phrase '.$tp['phrase'].' in der Sprache '.$tp['phrasentext']['sprache'].' angelegt!';
		}
	}
	else
		echo '<br>Phrasentext für Phrase '.$tp['phrase'].' existiert bereits in der Sprache '.$tp['phrasentext']['sprache'].'!';
}

echo '<br>Aktualisierung abgeschlossen<br><br>';

// Check if Phrasentext in DB is not in this Array
echo '<h2>Gegencheck</h2>';
// prepare NOT IN
$notin = "''";
foreach ($textphrasen as $tp)
{
	$notin.= ", '".$tp['phrase']."'";
}
$qry = "SELECT * FROM system.tbl_phrase WHERE app='aufnahme' AND phrase NOT IN (".$notin.');';
$res = $db->db_query($qry);
if ($db->db_num_rows() >0)
{
	while($row = $db->db_fetch_object())
		var_dump($row);
}
else
	echo 'Keine zusätzlichen Phrasen in der DB vorhanden!';
?>
