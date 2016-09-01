<?php
/**
 * ./tests/codeception/tests/acceptance/RegistrationCept.php
 *
 * @package default
 */


$I = new AcceptanceTester($scenario);

$I->wantTo("Register me, log me and get data from DB");

$I->amOnPage("cis/index.php");

// Registration
$I->see("Willkommen bei der Online Bewerbung");

// Checks if elements are present
$I->seeElement("#RegistrationLoginForm #vorname");
$I->seeElement("#RegistrationLoginForm #nachname");
$I->seeElement("#RegistrationLoginForm #geb_datum");
$I->seeElement("#RegistrationLoginForm #datenschutz");
$I->seeElement("#RegistrationLoginForm #registration_button");

// Fills elements
$I->fillField("#RegistrationLoginForm #vorname", "Code");
$I->fillField("#RegistrationLoginForm #nachname", "Ception");
$I->fillField("#RegistrationLoginForm #geb_datum", "01.01.1981");
$I->fillField("#RegistrationLoginForm #email", "codeception@fhcomplete.org");
$I->fillField("#RegistrationLoginForm #email2", "codeception@fhcomplete.org");
$I->fillField("#RegistrationLoginForm input[id=captcha] ", "123456");
$I->checkOption("#RegistrationLoginForm #datenschutz");

// Submit registration form
$I->click("#RegistrationLoginForm #registration_button");

// Getting data from database
$zugangscode = $I->grabFromDatabase("public.tbl_person", "zugangscode", array("vorname" => "Code", "nachname" => "Ception"));
if (!isset($zugangscode) || $zugangscode == "") {
	$I->expect("Zugangscode to be set, it is NOT!");
}
else {
	$I->comment("Zugangscode is: " . $zugangscode);
}

$I->amOnPage("cis/index.php");

// Login
$I->see("Willkommen bei der Online Bewerbung");

// Checks if elements are present
$I->seeElement("#LoginForm input[name=\"email\"]");
$I->seeElement("#LoginForm input[name=\"code\"]");
$I->seeElement("#LoginForm button[name=\"submit_btn\"]");

// Fills elements
$I->fillField("#LoginForm input[name=\"email\"]", "codeception@fhcomplete.org");
$I->fillField("#LoginForm input[name=\"code\"]", $zugangscode);

// Submit login form
$I->click("#LoginForm button[name=\"submit_btn\"]");

// Checks if it is on login page
$I->seeInCurrentUrl("/aufnahme/cis/index.dist.php/Studiengaenge");

// Checks if elements are present in login page
$I->seeElement("h2[class=\"stg_header\"]");
$I->see("Studiengänge Auswahl:");
