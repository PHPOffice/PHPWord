<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord_Writer_Word2007;
use PHPWord_Writer_Word2007_Base;

/**
 * Class PHPWord_Writer_Word2007_BaseTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class PHPWord_Writer_Word2007_BaseTest extends \PHPUnit_Framework_TestCase {
  /**
   * Executed before each method of the class
   */
  public function tearDown()
  {
    TestHelperDOCX::clear();
  }

  public function testWriteImage_Position()
  {
    $PHPWord = new PHPWord();
    $section = $PHPWord->createSection();
    $section->addImage(
      PHPWORD_TESTS_DIR_ROOT . '/_files/images/earth.jpg',
      array(
        'marginTop' => -1,
        'marginLeft' => -1,
        'wrappingStyle' => 'behind'
      )
    );

    $doc = TestHelperDOCX::getDocument($PHPWord);
    $element = $doc->getElement('/w:document/w:body/w:p/w:r/w:pict/v:shape');

    $style = $element->getAttribute('style');

    $this->assertRegExp('/z\-index:\-[0-9]*/', $style);
    $this->assertRegExp('/position:absolute;/', $style);
  }
}
 