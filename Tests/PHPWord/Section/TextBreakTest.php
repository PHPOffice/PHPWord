<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Section_TextBreak;

class PHPWord_Section_TextBreakTest extends \PHPUnit_Framework_TestCase {
  /**
   * Executed before each method of the class
   */
  public function testConstruct() {
    // Section Settings
    $oTextBreak = new PHPWord_Section_TextBreak();

    $this->assertInstanceOf('PHPWord_Section_TextBreak', $oTextBreak);
  }
}
 