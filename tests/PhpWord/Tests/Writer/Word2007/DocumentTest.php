<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;
use PhpOffice\PhpWord\Writer\Word2007\Document;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Document
 *
 * @runTestsInSeparateProcesses
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write word/document.xm with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testWriteDocumentNoPhpWord()
    {
        $object = new Document();
        $object->writeDocument();
    }

    /**
     * Write end section page numbering
     */
    public function testWriteEndSectionPageNumbering()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $settings = $section->getSettings();
        $settings->setLandscape();
        $settings->setPageNumberingStart(2);
        $settings->setBorderSize(240);
        $settings->setBreakType('nextPage');

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        $this->assertEquals(2, $element->getAttribute('w:start'));
    }

    /**
     * Write elements
     */
    public function testElements()
    {
        $objectSrc = __DIR__ . "/../../_files/documents/sheet.xls";

        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, array('color' => '333333', 'bold'=>true));
        $phpWord->addTitleStyle(2, array('color'=>'666666'));
        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addPageBreak();
        $section->addTitle('Title 1', 1);
        $section->addListItem('List Item 1', 0);
        $section->addListItem('List Item 2', 0);
        $section->addListItem('List Item 3', 0);
        $section = $phpWord->addSection();
        $section->addTitle('Title 2', 2);
        $section->addObject($objectSrc);
        $doc = TestHelperDOCX::getDocument($phpWord);

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:tabs/w:tab');
        $this->assertEquals('right', $element->getAttribute('w:val'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
        $this->assertEquals(9062, $element->getAttribute('w:pos'));

        // Page break
        $element = $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:br');
        $this->assertEquals('page', $element->getAttribute('w:type'));

        // Title
        $element = $doc->getElement('/w:document/w:body/w:p[5]/w:pPr/w:pStyle');
        $this->assertEquals('Heading1', $element->getAttribute('w:val'));

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[6]/w:pPr/w:numPr/w:numId');
        $this->assertEquals(3, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[11]/w:r/w:object/o:OLEObject');
        $this->assertEquals('Embed', $element->getAttribute('Type'));
    }

    /**
     * Write element with some styles
     */
    public function testElementStyles()
    {
        $objectSrc = __DIR__ . "/../../_files/documents/sheet.xls";

        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('pStyle', array('align' => 'center')); // Style #1
        $phpWord->addFontStyle('fStyle', array('size' => '20')); // Style #2
        $phpWord->addTitleStyle(1, array('color' => '333333', 'bold' => true)); // Style #3
        $fontStyle = new Font('text', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addListItem('List Item', 0, null, null, 'pStyle'); // Style #4
        $section->addObject($objectSrc, array('align' => 'center'));
        $section->addTOC($fontStyle);
        $section->addTitle('Title 1', 1);
        $section->addTOC('fStyle');
        $doc = TestHelperDOCX::getDocument($phpWord);

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId');
        $this->assertEquals(4, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:object/o:OLEObject');
        $this->assertEquals('Embed', $element->getAttribute('Type'));

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[3]/w:pPr/w:tabs/w:tab');
        $this->assertEquals('right', $element->getAttribute('w:val'));
        $this->assertEquals('dot', $element->getAttribute('w:leader'));
        $this->assertEquals(9062, $element->getAttribute('w:pos'));
    }
}
