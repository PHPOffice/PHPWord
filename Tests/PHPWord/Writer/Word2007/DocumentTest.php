<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord;
use PHPWord_Writer_Word2007;
use PHPWord_Writer_Word2007_Document;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class DocumentTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    public function testWriteEndSectionPageNumbering()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $section->getSettings()->setPageNumberingStart(2);

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        $this->assertEquals(2, $element->getAttribute('w:start'));
    }
}
