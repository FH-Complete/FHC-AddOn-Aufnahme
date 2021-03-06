<?php
/**
 * ./cis/application/config/aufnahme.php
 *
 * @package default
 */


defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| Default Language if no other is defined from Session or GET Params.
|
*/
$config['default_language'] = 'german';

/*
|--------------------------------------------------------------------------
| Theme
|--------------------------------------------------------------------------
|
| Must be the same name as the directory in the "themes" directory.
|
*/
$config['theme'] = 'fhstp';

/*
|--------------------------------------------------------------------------
| Phrases
|--------------------------------------------------------------------------
|
| If set to true, phrases will be fetched on login and stored in session.
|
*/
$config['store_phrases_in_session'] = false;

/*
|--------------------------------------------------------------------------
| Vorlagen Root OE
|--------------------------------------------------------------------------
|
| OE to get Email template for registration mail.
|
*/
$config['root_oe'] = 'etw';

/*
|--------------------------------------------------------------------------
| Root OE for study programs
|--------------------------------------------------------------------------
|
|
*/
$config['root_oe_stg'] = '';

/*
|--------------------------------------------------------------------------
| FHC-Models
|--------------------------------------------------------------------------
|
| Relative path to the Models from FHC-Core
|
*/
$config['fhc_models'] = '../../../../../application/models/';

/*
|--------------------------------------------------------------------------
| FHC-Core API
|--------------------------------------------------------------------------
|
| URL to the FHC-Core API
|
*/
$config['fhc_api'] = array(
	'server' => 'http://localhost/fhcomplete/index.ci.php/api/v1/',
	'api_key'         => 'testapikey@fhcomplete.org',
	'api_name'        => 'FHC-API-KEY',
	'http_user'       => 'admin',
	'http_pass'       => '1q2w3',
	'http_auth'       => 'basic',
	//'ssl_verify_peer' => TRUE,
	//'ssl_cainfo'      => '/certs/cert.pem'
);

/*
|--------------------------------------------------------------------------
| Profiler
|--------------------------------------------------------------------------
|
| Sets the Profiler in MY_Controller.
| Default should be false, only for Benchark testing it could be set true.
|
*/
$config['profiler'] = false;

/*
|--------------------------------------------------------------------------
| Main Menu
|--------------------------------------------------------------------------
|
| ordered list of Main Menu Entries
|
*/

$config['menu'][] = array('href' => site_url('Studiengaenge'), 'name' => array('Studiengänge', 'Study Programs'), 'id' => 'Studiengänge');
$config['menu'][] = array('href' => site_url('Bewerbung'), 'name' => array('Meine Bewerbungen', "My Applications"), 'id' => 'Bewerbung');
$config['menu'][] = array('href' => site_url('Aufnahmetermine'), 'name' => array('Aufnahmetermine','Admission Appointments'), 'id' => 'Aufnahmetermine');
$config['menu'][] = array('href' => site_url('Dokumente'), 'name' => array('Meine Dokumente','My Documents'), 'id' => 'Dokumente');
$config['menu'][] = array('href' => site_url('Messages'), 'name' => array('Nachrichten','Messages'), 'id' => 'Nachrichten');
$config['menu'][] = array('href' => site_url('Downloads'), 'name' => array('Downloads','Downloads'), 'id' => 'Downloads');

/*
|--------------------------------------------------------------------------
| Views to load in core-views
|--------------------------------------------------------------------------
|
| ordered list of detailed Views to load
|
*/
$config['view_login'][] = 'login/code_login';
$config['view_login'][] = 'login/uid_login';

$config['view_bewerbung_studiengang'][] = 'bewerbung/studiengang';
$config['view_bewerbung'][] = 'person/person';
$config['view_bewerbung'][] = 'bewerbung/person_udf';

$config['view_contact'][] = 'person/contact';

$config['view_overview'][] = 'overview/status';

$config['view_studiengaenge'][] = 'studiengaenge/header';
$config['view_studiengaenge'][] = 'studiengaenge/bachelor';
$config['view_studiengaenge'][] = 'studiengaenge/master';

$config['view_requirements'][] = 'requirements/requirements_allgemein';
$config['view_requirements'][] = 'bewerbung/spezialisierung';
$config['view_requirements'][] = 'requirements/requirements_spezifisch';
$config['view_requirements'][] = 'requirements/requirements_udf';

$config['view_summary'][] = 'summary/summary_personal';
$config['view_summary'][] = 'summary/summary_personal_udf';
$config['view_summary'][] = 'summary/summary_requirements';
$config['view_summary'][] = 'summary/summary_requirements_specific';
$config['view_summary'][] = 'summary/summary_requirements_udf';

$config['view_send'][] = 'send/send';

$config['view_aufnahmetermine'][] = 'aufnahmetermine/bachelor_termine';
$config['view_aufnahmetermine'][] = 'aufnahmetermine/master_termine';

$config['view_messages'][] = 'messages/messages';

$config['view_registration'][] = 'registration/registration';
$config['view_registration'][] = 'login/hybrid_login';

$config['view_dokumente'][] = 'dokumente/dokumente';


/*
|--------------------------------------------------------------------------
| Tab to load in Aufnhame view
|--------------------------------------------------------------------------
|
| list of tabs with their language file representation
|
 */
//$config['aufnahme_tabs'][] = array("label"=>"aufnahme/studiengaenge", "id"=>"studiengaenge");
//$config['aufnahme_tabs'][] = array("label"=>"aufnahme/termine", "id"=>"termine");
//$config['aufnahme_tabs'][] = array("label"=>"aufnahme/nachrichten", "id"=>"nachrichten");
//$config['aufnahme_tabs'][] = array("label"=>"aufnahme/downloads", "id"=>"downloads");

$config['display_phrase_name'] = false;

/*
|--------------------------------------------------------------------------
| Enable/Disalbe Hybrid Login
| first try is username/code
| second try is email/password
|--------------------------------------------------------------------------
|
| OE to get Email template for registration mail.
|
*/
$config['hybrid_login'] = true;

/*
|--------------------------------------------------------------------------
| Organisation Data
|--------------------------------------------------------------------------
|
| OE to get Email template for registration mail.
|
*/
$config['organisation'] = array(
	"bezeichnung" => "Fachhochschule St. Pölten",
	"strasse" => "Matthias Corvinus-Straße 15",
	"plz" => "3100",
	"ort" => "St. Pölten",
	"telefon" => "+43/2742/313 228 - 200",
	"fax" => "+43/2742/313 228 - 339",
	"mail" => "<a href='mailto:csc@fhstp.ac.at'>csc@fhstp.ac.at</a>"
);

/*
|--------------------------------------------------------------------------
| Impressum Link displayed in the footer
|--------------------------------------------------------------------------
*/
$config["impressumLink"] = "<a href='https://www.fhstp.ac.at/de/impressum'>Impressum</a>";

/*
|--------------------------------------------------------------------------
| Anfahrt Link displayed in the footer
|--------------------------------------------------------------------------
*/
$config["anfahrtLink"] = "<a href='https://www.fhstp.ac.at/de/anfahrt'>Anfahrt</a>";

/*
|--------------------------------------------------------------------------
| Mapping zu den Dokumenttypen in der Datenbank
|--------------------------------------------------------------------------
*/
$config['dokumentTypen'] = array(
	"reisepass" => "pass",
	"lebenslauf" => "Lebenslf",
	"abschlusszeugnis_b" => "DEB2",
    "abschlusszeugnis_m" => "Maturaze",
	"letztGueltigesZeugnis" => "Sonst",
	"sonstiges" => "Sonst"
);

/*
|--------------------------------------------------------------------------
| Email used for testing purposes, if is it used captcha check will be disabled
|--------------------------------------------------------------------------
*/
$config['codeception_email'] = 'codeception@fhcomplete.org';

/*
|--------------------------------------------------------------------------
| Defines how many hours registration link is valid after the first registration
| (in hours)
|--------------------------------------------------------------------------
*/
$config['invalidateRegistrationTimestampAfter']  = 24;

/*
|--------------------------------------------------------------------------
| Defines how many hours registration link is valid when the user
| requested the link again (in hours)
|--------------------------------------------------------------------------
*/
$config['invalidateResendTimestampAfter']  = 1;

/*
|--------------------------------------------------------------------------
| Link zur Datenschutzerklärung
| wird nicht angezeigt, wenn Leerstring
|--------------------------------------------------------------------------
*/
$config['LinkDatenschutz'] = "https://www.fhstp.ac.at/de/anmeldung/datenschutz";

/*
|--------------------------------------------------------------------------
| Aktiviert/Deaktiviert den Google Tag Manager
|--------------------------------------------------------------------------
*/
$config['GoogleTagManager'] = false;

/*
|--------------------------------------------------------------------------
| Skript des DataLayer für den GoogleTagManager
|--------------------------------------------------------------------------
*/
$config['GoogleTagManagerDataLayer'] = "<script>
			dataLayer = [];
		  </script>";

/*
|--------------------------------------------------------------------------
| Skript des Google Tag Managers im Html-Head
|--------------------------------------------------------------------------
*/
$config['GoogleTagManagerScriptHead'] = "";

/*
|--------------------------------------------------------------------------
| Skript des Google Tag Managers im Html-Body
|--------------------------------------------------------------------------
*/
$config['GoogleTagManagerScriptBody'] = '';

/*
|--------------------------------------------------------------------------
| ZGV Options Mapping to STG Typ
|--------------------------------------------------------------------------
*/
$config['ZgvOptionsMapping'] = array(
	"b" => "ZGV/OptionsBachelor",
	"m" => "ZGV/OptionsMaster"
);

/*
|--------------------------------------------------------------------------
| Path to a directory where documents can be temporarily created for downlaod
| Documents will be deleted automatically after download
|--------------------------------------------------------------------------
*/
$config['document_download_path'] = "";

/*
|--------------------------------------------------------------------------
| Anzeige des Containers für User Defined Fields im Bereich Personal Data
| Hint: Do not forget to check if the view is activated
| data saved to person & prestudent
|--------------------------------------------------------------------------
*/
$config['udf_container_personal_data'] = array(
    "active" => false, //true | false
    "className" => "udf_personal_data",
    "udfs" => array() //"udf_schuhgroesse", "udf_nickname", "udf_age", "udf_disagree", "udf_agree"
);

/*
|--------------------------------------------------------------------------
| Anzeige des Containers für User Defined Fields im Bereich Requirements
| Hint: Do not forget to check if the view is activated
| data saved to person & prestudent
|--------------------------------------------------------------------------
*/
$config['udf_container_requirements'] = array(
    "active" => false, //true | false
    "className" => "udf_requirements",
    "udfs" => array()
);
