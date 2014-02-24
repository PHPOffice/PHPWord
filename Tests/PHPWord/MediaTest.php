<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Media;

class PHPWord_MediaTest extends \PHPUnit_Framework_TestCase {

  public function testGetSectionMediaElementsWithNull()
  {
    $this->assertEquals(PHPWord_Media::getSectionMediaElements(), array());
  }

  public function testCountSectionMediaElementsWithNull()
  {
    $this->assertEquals(PHPWord_Media::countSectionMediaElements(), 0);
  }

  public function testGetHeaderMediaElements()
  {
    $this->assertAttributeEquals(PHPWord_Media::getHeaderMediaElements(), '_headerMedia','PHPWord_Media');
  }

  public function testGetFooterMediaElements()
  {
    $this->assertAttributeEquals(PHPWord_Media::getFooterMediaElements(), '_footerMedia','PHPWord_Media');
  }
}
 