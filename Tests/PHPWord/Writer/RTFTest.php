<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_RTF;
use PHPWord;

/**
 * Class RTFTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Writer_RTF(new PHPWord);

        $this->assertInstanceOf('PHPWord', $object->getPHPWord());
        $this->assertInstanceOf("PHPWord_HashTable", $object->getDrawingHashTable());
    }

    /**
     * Test construct with null value/without PHPWord
     *
     * @expectedException Exception
     * @expectedExceptionMessage No PHPWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new PHPWord_Writer_RTF();
        $object->getPHPWord();
    }

    /**
     * Test save()
     */
    public function testSave()
    {
        $phpWord = new PHPWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2');
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');

        $writer = new PHPWord_Writer_RTF($phpWord);
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.rtf')
        );
        $writer->save($file);
        $this->assertTrue(file_exists($file));
        unlink($file);
    }
}
