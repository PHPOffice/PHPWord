<?php
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
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

    public function testWriteEndSectionPageNumbering()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();
        $section->getSettings()->setPageNumberingStart(2);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        $this->assertEquals(2, $element->getAttribute('w:start'));
    }

    /**
     * covers ::_writeTOC
     * covers ::_writePageBreak
     * covers ::_writeListItem
     * covers ::_writeTitle
     * covers ::_writeObject
     */
    public function testElements()
    {
        $objectSrc = __DIR__ . "/../../_files/documents/sheet.xls";

        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, array('color' => '333333', 'bold'=>true));
        $phpWord->addTitleStyle(2, array('color'=>'666666'));
        $section = $phpWord->createSection();
        $section->addTOC();
        $section->addPageBreak();
        $section->addTitle('Title 1', 1);
        $section->addListItem('List Item 1', 0);
        $section->addListItem('List Item 2', 0);
        $section->addListItem('List Item 3', 0);
        $section = $phpWord->createSection();
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
}
