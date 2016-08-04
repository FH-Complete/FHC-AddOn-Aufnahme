<?php

$I = new AcceptanceTester($scenario);

$I->wantTo("How much codeception could be lame!!!");

$I->amOnPage("cis/index.php");

$I->see("Willkommen bei der Online Bewerbung");

$I->seeElement("#vorname");
$I->seeElement("#nachname");
$I->seeElement("#geb_datum");
$I->seeElement("#datenschutz");
$I->seeElement("#registration_button");


$I->fillField("#vorname", "test 1");
$I->fillField("#nachname", "test 2");
$I->fillField("#geb_datum", "2016.08.01");
$I->fillField("#wohnort", "here");
$I->fillField("#email", "test@test.com");
$I->fillField("#email2", "test@test.com");

$I->checkOption("#datenschutz");

$I->click("#registration_button");