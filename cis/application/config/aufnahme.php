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

$config['menu'][] = array('href' => site_url('Studiengaenge'), 'name' => 'Studiengänge', 'id' => 'Studiengänge');
$config['menu'][] = array('href' => site_url('Bewerbung'), 'name' => 'meine Bewerbungen', 'id' => 'Bewerbung');
$config['menu'][] = array('href' => site_url('Aufnahmetermine'), 'name' => 'Aufnahmetermine', 'id' => 'Aufnahmetermine');
$config['menu'][] = array('href' => site_url('Dokumente'), 'name' => 'Dokumente', 'id' => 'Dokumente');
$config['menu'][] = array('href' => site_url('Messages'), 'name' => 'Nachrichten', 'id' => 'Nachrichten');
$config['menu'][] = array('href' => site_url('Downloads'), 'name' => 'Downloads', 'id' => 'Downloads');

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
$config['view_bewerbung'][] = 'bewerbung/spezialisierung';

$config['view_contact'][] = 'person/contact';

$config['view_overview'][] = 'overview/status';

$config['view_studiengaenge'][] = 'studiengaenge/header';
$config['view_studiengaenge'][] = 'studiengaenge/bachelor';
$config['view_studiengaenge'][] = 'studiengaenge/master';

$config['view_requirements'][] = 'requirements/requirements_allgemein';
$config['view_requirements'][] = 'requirements/requirements_spezifisch';
//$config['view_requirements'][] = 'requirements/requirements_motivation';

$config['view_summary'][] = 'summary/summary_personal';
$config['view_summary'][] = 'summary/summary_requirements';
$config['view_summary'][] = 'summary/summary_requirements_specific';
//$config['view_summary'][] = 'summary/summary_motivation';

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
	"fax" => "F: +43/2742/313 228 - 339",
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
	"abschlusszeugnis" => "Maturaze",
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
