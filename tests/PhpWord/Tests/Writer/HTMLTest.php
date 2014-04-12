<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML
 *
 * @runTestsInSeparateProcesses
 */
class HTMLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new HTML(new PhpWord);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());
    }

    /**
     * Construct with null
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new HTML();
        $object->getPhpWord();
    }

    /**
     * Save
     */
    public function testSave()
    {
        $imageSrc = __DIR__ . "/../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.html";

        $phpWord = new PhpWord();

        $docProps = $phpWord->getDocumentProperties();
        $docProps->setTitle('HTML Test');

        $phpWord->addFontStyle('Font', array('name' => 'Verdana', 'size' => 11, 'color' => 'FF0000', 'fgColor' => 'FF0000'));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2', array('name' => 'Tahoma', 'bold' => true, 'italic' => true));
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addFootnote();
        $section->addEndnote();

        $section = $phpWord->addSection();

        $textrun = $section->addTextRun(array('align' => 'center'));
        $textrun->addText('Test 3');
        $textrun->addTextBreak();

        $textrun = $section->addTextRun('Paragraph');
        $textrun->addLink('http://test.com');
        $textrun->addImage($imageSrc);
        $textrun->addFootnote();
        $textrun->addEndnote();

        $section = $phpWord->addSection();

        $table = $section->addTable();
        $cell = $table->addRow()->addCell();
        $cell->addText('Test 1', array('superscript' => true, 'underline' => 'dash', 'strikethrough' => true));
        $cell->addTextRun();
        $cell->addLink('http://test.com');
        $cell->addTextBreak();
        $cell->addListItem('Test');
        $cell->addImage($imageSrc);
        $cell->addObject($objectSrc);
        $cell->addFootnote();
        $cell->addEndnote();

        $writer = new HTML($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }
}
