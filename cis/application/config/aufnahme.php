<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
    'server' => APP_ROOT.'index.ci.php/api/v1/',
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
| Main Menu
|--------------------------------------------------------------------------
|
| ordered list of Main Menu Entries
|
*/
$config['menu'][] = array('href' => site_url('Studiengaenge'), 'name' => 'Studiengänge');
$config['menu'][] = array('href' => site_url('Bewerbung'), 'name' => 'Bewerbung', 'glyphicon' => 'glyphicon-ok');
$config['menu'][] = array('href' => site_url('Contact'), 'name' => 'Aufnahmetermine');
$config['menu'][] = array('href' => site_url('Admittance'), 'name' => 'Nachrichten');
$config['menu'][] = array('href' => site_url('Documents'), 'name' => 'Downloads');
$config['menu'][] = array('href' => site_url('Logout'), 'name' => 'Logout', 'glyphicon' => 'glyphicon-log-out');

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

$config['view_contact'][] = 'person/contact';

$config['view_overview'][] = 'overview/status';

$config['view_studiengaenge'][] = 'studiengaenge/header';
$config['view_studiengaenge'][] = 'studiengaenge/bachelor';
$config['view_studiengaenge'][] = 'studiengaenge/master';

$config['view_requirements'][] = 'requirements/requirements';

$config['view_summary'][] = 'summary/summary';

$config['view_send'][] = 'send/send';



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

