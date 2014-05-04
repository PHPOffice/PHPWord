<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\ODText\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\ODText\Part\Content;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\Content
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\Content
 * @runTestsInSeparateProcesses
 */
class ContentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test construct with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructNoPhpWord()
    {
        $object = new Content();
        $object->writeContent();
    }

    /**
     * Test write content
     */
    public function testWriteContent()
    {
        $imageSrc = __DIR__ . "/../../../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../../../_files/documents/sheet.xls";
        $expected = 'Expected';

        $phpWord = new PhpWord();

        $phpWord->setDefaultFontName('Verdana');
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));

        $section = $phpWord->addSection();
        $section->addText($expected);
        $section->addText('Test font style', 'Font');
        $section->addText('Test paragraph style', null, 'Paragraph');
        $section->addLink('http://test.com', 'Test link');
        $section->addTitle('Test title', 1);
        $section->addTextBreak();
        $section->addPageBreak();
        $section->addListItem('Test list item');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();

        $textrun = $section->addTextRun();
        $textrun->addText('Test text run');

        $table = $section->addTable();
        $cell = $table->addRow()->addCell();
        $cell = $table->addRow()->addCell();
        $cell->addText('Test');
        $cell->addLink('http://test.com', 'Test link');
        $cell->addTextBreak();
        $cell->addListItem('Test list item');
        $cell->addImage($imageSrc);
        $cell->addObject($objectSrc);
        $textrun = $cell->addTextRun();
        $textrun->addText('Test text run');

        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = "/office:document-content/office:body/office:text/text:p";
        $this->assertEquals($expected, $doc->getElement($element, 'content.xml')->nodeValue);
    }

    /**
     * Test no paragraph style
     */
    public function testWriteNoStyle()
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = "/office:document-content/office:automatic-styles/style:style";
        $this->assertTrue($doc->elementExists($element, 'content.xml'));
    }
}
