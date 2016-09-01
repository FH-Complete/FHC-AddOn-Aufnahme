<?php
/**
 * ./tests/phpunit/PHPUnitTest.php
 *
 * @package default
 */


//require_once "PHPUnit/Autoload.php";

class PHPUnitTest extends PHPUnit_Framework_TestCase
{


	/**
	 *
	 * @return unknown
	 */
	function testPHPUnitEmpty() {
		$myDebugVar = array();
		$this->assertEmpty($myDebugVar);

		return $myDebugVar;
	}





	/**
	 *
	 * @depends testPHPUnitEmpty
	 * @param array   $myDebugVar
	 * @return unknown
	 */
	function testPHPUnitPush(array $myDebugVar) {
		array_push($myDebugVar, 'This is');
		array_push($myDebugVar, 'a working');
		array_push($myDebugVar, 'PHPUnit-Test!');
		$this->assertEquals('PHPUnit-Test!', $myDebugVar[count($myDebugVar)-1]);
		$this->assertNotEmpty($myDebugVar);

		return $myDebugVar;
	}





	/**
	 *
	 * @depends testPHPUnitPush
	 * @param array   $myDebugVar
	 */
	function testPHPUnitPop(array $myDebugVar) {
		fwrite(STDERR, print_r($myDebugVar, TRUE));
		$this->assertEquals('PHPUnit-Test!', array_pop($myDebugVar));
		$this->assertEquals('a working', array_pop($myDebugVar));
		$this->assertEquals('This is', array_pop($myDebugVar));
		$this->assertEmpty($myDebugVar);
	}


}
