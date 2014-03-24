<?php
namespace PhpOffice\PhpWord\Tests\Writer\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Writer\ODText\Content
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
     * covers ::writeContent
     * covers <private>
     */
    public function testWriteContent()
    {
        $imageSrc = __DIR__ . "/../../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../../_files/documents/sheet.xls";
        $expected = 'Expected';

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Verdana');
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText($expected);
        $section->addText('Test font style', 'Font');
        $section->addText('Test paragraph style', null, 'Paragraph');
        $section->addTextBreak();
        $section->addLink('http://test.com', 'Test link');
        $section->addTitle('Test title', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test list item');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $textrun = $section->createTextRun();
        $textrun->addText('Test text run');
        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = "/office:document-content/office:body/office:text/text:p";
        $this->assertEquals($expected, $doc->getElement($element, 'content.xml')->nodeValue);
    }
}
