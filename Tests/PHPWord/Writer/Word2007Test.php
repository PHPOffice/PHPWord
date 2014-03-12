<?php
namespace PHPWord\Tests\Writer;

use PHPUnit_Framework_TestCase;
use PHPWord_Writer_Word2007;
use PHPWord;

/**
 * Class Word2007Test
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Writer_Word2007(new PHPWord());

        $writerParts = array('ContentTypes', 'Rels', 'DocProps',
            'DocumentRels', 'Document', 'Styles', 'Header', 'Footer',
            'Footnotes', 'FootnotesRels');
        foreach ($writerParts as $part) {
            $this->assertInstanceOf(
                "PHPWord_Writer_Word2007_{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                "PHPWord_Writer_Word2007",
                $object->getWriterPart($part)->getParentWriter()
            );
        }
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

        $writer = new PHPWord_Writer_Word2007($phpWord);
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.docx')
        );
        $writer->save($file);
        $this->assertTrue(file_exists($file));
        unlink($file);
    }
}
