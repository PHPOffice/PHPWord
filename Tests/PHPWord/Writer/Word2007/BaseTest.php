<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class BaseTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    public function testWriteParagraphStyleAlign()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $section->addText('This is my text', null, array('align' => 'right'));

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:jc');

        $this->assertEquals('right', $element->getAttribute('w:val'));
    }

    /**
     * Test write paragraph pagination
     */
    public function testWriteParagraphStylePagination()
    {
        // Create the doc
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $attributes = array(
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        );
        foreach ($attributes as $attribute => $value) {
            $section->addText('Test', null, array($attribute => $value));
        }
        $doc = TestHelperDOCX::getDocument($PHPWord);

        // Test the attributes
        $i = 0;
        foreach ($attributes as $key => $value) {
            $i++;
            $path = "/w:document/w:body/w:p[{$i}]/w:pPr/w:{$key}";
            $element = $doc->getElement($path);
            $expected = $value ? 1 : 0;
            $this->assertEquals($expected, $element->getAttribute('w:val'));
        }
    }

    public function testWriteCellStyleCellGridSpan()
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

    public function testWriteImagePosition()
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

    public function testWritePreserveText()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $footer = $section->createFooter();

        $footer->addPreserveText('{PAGE}');

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $preserve = $doc->getElement("w:p/w:r[2]/w:instrText", 'word/footer1.xml');

        $this->assertEquals('PAGE', $preserve->nodeValue);
        $this->assertEquals('preserve', $preserve->getAttribute('xml:space'));
    }
}
