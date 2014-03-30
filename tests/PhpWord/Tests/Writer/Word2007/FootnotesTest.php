<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Footnotes
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Footnotes
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
        $phpWord->addParagraphStyle('pStyle', array('align' => 'left'));
        $section = $phpWord->createSection();
        $section->addText('Text');
        $footnote1 = $section->createFootnote('pStyle');
        $footnote1->addText('Footnote');
        $footnote1->addTextBreak();
        $footnote1->addLink('http://google.com');
        $footnote2 = $section->createFootnote(array('align' => 'left'));
        $footnote2->addText('Footnote');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists("/w:document/w:body/w:p/w:r/w:footnoteReference"));
    }
}
