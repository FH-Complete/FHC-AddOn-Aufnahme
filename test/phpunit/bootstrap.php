<?php
// UnitTest-Test
$path = __DIR__ . '/';
$phpunittest = 'PHPUnitTest.php';
$i = 0;
while (! file_exists($path . $phpunittest) && ! file_exists($path . 'htdocs/' . $phpunittest) && $i++ < 10) {
    $path .= '../';
}
chdir(file_exists($path . $phpunittest) ? $path : $path . 'htdocs');
//require_once $phpunittest;
// require_once 'vendor/autoload.php';
//PHPUnitTest::testPHPUnitEmpty(true);
