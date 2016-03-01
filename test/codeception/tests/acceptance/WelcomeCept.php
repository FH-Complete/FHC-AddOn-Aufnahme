<?php 
// @group login

$I = new AcceptanceTester($scenario);
$I->am('Anonymous User'); 
$I->wantTo('ensure that Home-Page works');

$I->lookForwardTo('check the Home Page');
$I->amOnPage('/index.html');
$I->see("window.location.href='index.php';");

$I->lookForwardTo('check the Welcome Page');
$I->amOnPage('/index.php');
$I->see('Willkommen');
