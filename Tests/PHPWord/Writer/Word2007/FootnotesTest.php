<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class PHPWord_Writer_Word2007_FootnotesTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class FootnotesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    public function testWriteFootnotes()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $section->addText('Text');
        $footnote = $section->createFootnote();
        $footnote->addText('Footnote');
        $footnote->addLink('http://google.com');
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $this->assertTrue($doc->elementExists("/w:document/w:body/w:p/w:r/w:footnoteReference"));
    }
}
