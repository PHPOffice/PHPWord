<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Section_PageBreak;

class PHPWord_Section_PageBreakTest extends \PHPUnit_Framework_TestCase {
  /**
   * Executed before each method of the class
   */
  public function testConstruct() {
    // Section Settings
    $oPageBreak = new PHPWord_Section_PageBreak();

    $this->assertInstanceOf('PHPWord_Section_PageBreak', $oPageBreak);
  }
}
 