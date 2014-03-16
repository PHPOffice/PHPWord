<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_RTF;
use PHPWord;

/**
 * Class RTFTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_RTF
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers   ::construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Writer_RTF(new PHPWord);

        $this->assertInstanceOf('PHPWord', $object->getPHPWord());
        $this->assertInstanceOf("PHPWord_HashTable", $object->getDrawingHashTable());
    }

    /**
     * covers                       ::__construct
     * @expectedException           Exception
     * @expectedExceptionMessage    No PHPWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new PHPWord_Writer_RTF();
        $object->getPHPWord();
    }

    /**
     * @covers                      ::save
     * @expectedException           Exception
     * @expectedExceptionMessage    PHPWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new PHPWord_Writer_RTF();
        $writer->save();
    }

    /**
     * @covers  ::save
     * @covers  ::<private>
     */
    public function testSave()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $objectSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $file = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.rtf')
        );

        $fontStyle = array('name' => 'Verdana', 'size' => 11, 'bold' => true, 'italic' => true, 'color' => 'F29101', 'fgColor' => '123456');
        $paragraphStyle = array('align' => 'center', 'spaceAfter' => 120);
        $phpWord = new PHPWord();
        $phpWord->addFontStyle('Font', $fontStyle);
        $phpWord->addParagraphStyle('Paragraph', $paragraphStyle);
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font');
        $section->addText('Test 2', array('name' => 'Tahoma'), 'Paragraph');
        $section->addTextBreak();
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');
        $textrun->addTextBreak();
        $writer = new PHPWord_Writer_RTF($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }
}
