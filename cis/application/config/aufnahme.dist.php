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
$config['theme'] = 'tw';

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
    'http_user'       => 'pam',
    'http_pass'       => '1q2w3',
    'http_auth'       => 'basic',
    //'ssl_verify_peer' => TRUE,
    //'ssl_cainfo'      => '/certs/cert.pem'
);


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



/*
|--------------------------------------------------------------------------
| Tab to load in Aufnhame view
|--------------------------------------------------------------------------
|
| list of tabs with their language file representation
|
 */
$config['aufnahme_tabs'][] = array("label"=>"aufnahme/studiengaenge", "id"=>"studiengaenge");
$config['aufnahme_tabs'][] = array("label"=>"aufnahme/termine", "id"=>"termine");
$config['aufnahme_tabs'][] = array("label"=>"aufnahme/nachrichten", "id"=>"nachrichten");
$config['aufnahme_tabs'][] = array("label"=>"aufnahme/downloads", "id"=>"downloads");

