<?php
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
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
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();
        $section->addText('Text');
        $footnote = $section->createFootnote();
        $footnote->addText('Footnote');
        $footnote->addLink('http://google.com');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists("/w:document/w:body/w:p/w:r/w:footnoteReference"));
    }
}
