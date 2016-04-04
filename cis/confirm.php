<?php

/* Copyright (C) 2016 FH Technikum-Wien
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
 * Authors: Stefan Puraner <stefan.puraner@technikum-wien.at
 */

require_once('../../../config/global.config.inc.php');
require_once('../../../config/cis.config.inc.php');
require_once('../../../include/phrasen.class.php');
require_once('../../../include/person.class.php');
require_once('../../../include/datum.class.php');
require_once('../../../include/mail.class.php');
require_once('../../../include/prestudent.class.php');
require_once('../../../include/preinteressent.class.php');
require_once('../../../include/kontakt.class.php');
require_once('../../../include/studiensemester.class.php');
require_once('../../../include/datum.class.php');
require_once('../../../include/sprache.class.php');
require_once('../../../include/benutzer.class.php');
require_once('../include/functions.inc.php');

require_once '../../../include/securimage/securimage.php';

session_start();
$lang = filter_input(INPUT_GET, 'lang');

if(isset($lang))
{
    setSprache($lang);
}

$method = filter_input(INPUT_GET, 'method');
$message = '';
$datum = new datum();

$sprache = filter_input(INPUT_GET, 'sprache');

if(isset($sprache))
{
    $sprache = new sprache();
    if($sprache->load($_GET['sprache']))
    {
	setSprache($_GET['sprache']);
    }
    else
	setSprache(DEFAULT_LANGUAGE);
}

$sprache = getSprache();
$p = new phrasen($sprache);
$code = trim(filter_input(INPUT_GET, 'code'));
$method = filter_input(INPUT_GET, 'method');
$zugangscode_alt = filter_input(INPUT_POST, 'zugangscode_alt');
$zugangscode_neu = filter_input(INPUT_POST, 'zugangscode_neu');
$password = filter_input(INPUT_POST, 'password');

var_dump($_POST);

?>
<!DOCTYPE html>
<html>
    <head>
	    <title>Bestätigung Ihrer Registrierung</title>
	    <meta http-equiv="X-UA-Compatible" content="chrome=1">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	    <meta name="robots" content="noindex">
	    <link href="../../../submodules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	    <link href="../include/css/registration.css" rel="stylesheet" type="text/css">
    </head>
    <body class="main">
	<div class="container">
	    <?php
	    $sprache2 = new sprache();
	    $sprache2->getAll(true);
	    ?>
	    <div class="dropdown pull-right">
		<button class="btn btn-default dropdown-toggle" type="button" id="sprache-label" data-toggle="dropdown" aria-expanded="true">
		    <?php echo $sprache2->getBezeichnung(getSprache(), getSprache()) ?>
		    <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="sprache-label" id="sprache-dropdown">
		    <?php foreach($sprache2->result as $row): ?>
			<li role="presentation">
			    <a href="#" role="menuitem" tabindex="-1" data-sprache="<?php echo $row->sprache ?>">
				<?php echo $row->bezeichnung_arr[getSprache()] ?>
			    </a>
			</li>
		    <?php endforeach; ?>
		</ul>
	    </div>
	    <?php
		if ($code && !isset($method))
		{
		    $person = new person();

		    //TODO check if zugangscode exists and is valid
		    $person_id = $person->checkZugangscodePerson($code);
		    //Zugangscode wird überprüft
		    if($person_id != false)
		    {
			$_SESSION['bewerbung/user'] = $code;
			$_SESSION['bewerbung/personId'] = $person_id;
			
			$kontakt = new kontakt();
			$kontakt->load_persKontakttyp($person_id, "email");
			
			$zugangscode_neu = substr(md5(openssl_random_pseudo_bytes(20)), 0, 10);
			$email = $kontakt->result[0]->kontakt;
			
			?>
	    
			<form id="RegistrationForm" name="RegistrationForm" class="form-horizontal" method="POST" action="<?php echo basename(__FILE__) ?>?method=registration&code="<?php echo $code;?>>
			    <div class="form-group">
				<div class="col-sm-4 col-sm-offset-3">
				    <?php
					echo $p->t("bewerbung/ihrPasswortLautet",$zugangscode_neu);
				    ?>
				</div>
			    </div>
			    <input type="hidden" name="zugangscode_alt" value="<?php echo $code;?>"/>
			    <input type="hidden" name="zugangscode_neu" value="<?php echo $zugangscode_neu;?>"/>
			    <div class="form-group">
				<label for="email" class="col-sm-3 control-label">
				    <?php echo $p->t('global/mail') ?>
				</label>
				<div class="col-sm-4">
				    <input type="text" maxlength="32" name="email" id="email" value="<?php echo $email ?>" class="form-control" disabled>
				</div>
			    </div>
			    <div class="form-group">
				<label for="email" class="col-sm-3 control-label">
				    <?php echo $p->t('global/passwort') ?>
				</label>
				<div class="col-sm-4">
				    <input type="password" maxlength="32" name="password" id="password" value="" class="form-control">
				</div>
			    </div>
			    <div class="form-group">
				<div class="col-sm-4 col-sm-offset-3">
				    <input type="submit" name="submit_btn" value="<?php echo $p->t('global/speichern') ?>" onclick="return checkRegistration() && validateEmail(document.RegistrationForm.email.value)" class="btn btn-primary">
				</div>
			    </div>
			</form>
		<?php
		    }
		    else
		    {
			$message = '<script type="text/javascript">alert("'.$p->t('bewerbung/zugangsdatenFalsch').'")</script>';
		    }
		}
		elseif(isset($method) && $method=="registration")
		{
		    if($password == $zugangscode_neu)
		    {
			$person = new person();
			$person_id = $person->checkZugangscodePerson($zugangscode_alt);

			if($person_id != false)
			{
			    $_SESSION['bewerbung/user'] = $zugangscode_alt;
			    $_SESSION['bewerbung/personId'] = $person_id;

			    $person->load($person_id);
			    $person->zugangscode = $zugangscode_neu;
			    if(!$person->save())
			    {
				$message = '<script type="text/javascript">alert("'.$p->t('bewerbung/fehler').'")</script>';
			    }
			    else
			    {
				header('Location: bewerbung.php');
			    }
			}
			else
			{
			    $message = '<script type="text/javascript">alert("'.$p->t('bewerbung/zugangsdatenFalsch').'")</script>';
			}
		    }
		    else
		    {
			$message = '<script type="text/javascript">alert("'.$p->t('bewerbung/zugangsdatenFalsch').'")</script>';
		    }
		    
		}
		else
		{
		    $message = '<script type="text/javascript">alert("'.$p->t('bewerbung/zugangsdatenFalsch').'")</script>';
		}
		?>
	</div>
	
	<script type="text/javascript">
	    function validateEmail(email) 
	    {
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		if(re.test(email)===false)
		{
		    alert("<?php echo $p->t('bewerbung/bitteEmailAngeben')?>");
		    return false;
		}
		else
		    return true;
	    };
	    
	    function checkRegistration()
	    {
		console.log(document);
		if(document.RegistrationForm.email.value == "")
		{
		    alert("<?php echo $p->t('bewerbung/bitteEmailAngeben')?>");
		    return false;
		}
		
		console.log(document.RegistrationForm.password.value);
		if(document.RegistrationForm.password.value == "")
		{
		    alert("<?php echo $p->t('bewerbung/bittePasswortAngeben')?>");
		    return false;
		}   
		return true;
	    }
	</script>
	<?php echo $message ?>
    </body>
</html>

<?php
function sendMail($zugangscode, $email, $person_id=null)
{
	if($person_id!='')
	{
	    $person = new person();
	    $person->load($person_id);
	    $vorname = $person->vorname;
	    $nachname  = $person->nachname;
	    $geschlecht = $person->geschlecht;
	}
	if($geschlecht=='m')
	{
	    $anrede=$p->t('bewerbung/anredeMaennlich');
	}
	else 
	{
	    $anrede=$p->t('bewerbung/anredeWeiblich');
	}

	$mail = new mail($email, 'no-reply', $p->t('bewerbung/registration'), $p->t('bewerbung/mailtextHtml'));
	$text = $p->t('bewerbung/mailtext',array($vorname, $nachname, $zugangscode, $anrede));
	$mail->setHTMLContent($text);
	if(!$mail->send())
	{
	    $msg= '<span class="error">'.$p->t('bewerbung/fehlerBeimSenden').'</span><br /><a href='.$_SERVER['PHP_SELF'].'?method=registration>'.$p->t('bewerbung/zurueckZurAnmeldung').'</a>';
	}
	else
	{
	    $msg= $p->t('bewerbung/emailgesendetan', array($email))."<br><br><a href=".$_SERVER['PHP_SELF'].">".$p->t('bewerbung/zurueckZurAnmeldung')."</a>";
	}
	return $msg;
}