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

  public function testWriteParagraphStyle_Align()
  {
    $PHPWord = new PHPWord();
    $section = $PHPWord->createSection();

    $section->addText('This is my text', null, array('align' => 'right'));

    $doc = TestHelperDOCX::getDocument($PHPWord);
    $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:jc');

    $this->assertEquals('right', $element->getAttribute('w:val'));
  }

  public function testWriteCellStyle_CellGridSpan()
  {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $table = $section->addTable();

        $table->addRow();
        $cell = $table->addCell(200);
        $cell->getStyle()->setGridSpan(5);

        $table->addRow();
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:gridSpan');

        $this->assertEquals(5, $element->getAttribute('w:val'));
  }

    /**
     * Test write paragraph pagination
     */
    public function testWriteParagraphStyle_Pagination()
    {
        // Create the doc
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $attributes = array(
            'widowControl' => 0,
            'keepNext' => 1,
            'keepLines' => 1,
            'pageBreakBefore' => 1,
        );
        foreach ($attributes as $attribute => $value) {
            $section->addText('Test', null, array($attribute => $value));
        }
        $doc = TestHelperDOCX::getDocument($PHPWord);

        // Test the attributes
        $i = 0;
        foreach ($attributes as $attribute => $value) {
            $i++;
            $path = "/w:document/w:body/w:p[{$i}]/w:pPr/w:{$attribute}";
            $element = $doc->getElement($path);
            $this->assertEquals($value, $element->getAttribute('w:val'));
        }
    }

}
