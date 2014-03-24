<?php
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Writer\RTF
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers ::construct
     */
    public function testConstruct()
    {
        $object = new RTF(new PhpWord);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\HashTable', $object->getDrawingHashTable());
    }

    /**
     * covers                    ::__construct
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new RTF();
        $object->getPhpWord();
    }

    /**
     * @covers ::save
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();
        $section->addText('Test');
        $writer = new RTF($phpWord);
        $writer->save('php://output');
    }

    /**
     * @covers                   ::save
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     * @expectedExceptionMessage PhpWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new RTF();
        $writer->save();
    }

    /**
     * @covers ::save
     * @covers ::<private>
     */
    public function testSave()
    {
        $imageSrc = __DIR__ . "/../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.rtf";

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
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
        $writer = new RTF($phpWord);
        $writer->save($file);

        $this->assertTrue(\file_exists($file));

        unlink($file);
    }
}
