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
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF
 *
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new RTF(new PhpWord);

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
        $object = new RTF();
        $object->getPhpWord();
    }

    /**
     * Save
     */
    public function testSave()
    {
        $imageSrc = __DIR__ . "/../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.rtf";

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('name' => 'Verdana', 'size' => 11, 'color' => 'FF0000', 'fgColor' => 'FF0000'));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2', array('name' => 'Tahoma', 'bold' => true, 'italic' => true));
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('Test 3');
        $textrun->addTextBreak();
        $writer = new RTF($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * Save
     *
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $writer = new RTF($phpWord);
        $writer->save('php://output');
    }

    /**
     * Save with no PhpWord object assigned
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage PhpWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new RTF();
        $writer->save();
    }
}
