<?php
namespace PHPWord\Tests\Writer\ODText;

use PHPWord;
use PHPWord_Style;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class ContentTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_ODText_Content
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
     * covers   ::writeContent
     * covers   <private>
     */
    public function testWriteContent()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $objectSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $expected = 'Expected';

        $PHPWord = new PHPWord();
        $PHPWord->setDefaultFontName('Verdana');
        $PHPWord->addFontStyle('Font', array('size' => 11));
        $PHPWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $PHPWord->createSection();
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
        $doc = TestHelperDOCX::getDocument($PHPWord, 'ODText');

        $element = "/office:document-content/office:body/office:text/text:p";
        $this->assertEquals($expected, $doc->getElement($element, 'content.xml')->nodeValue);
    }
}
