<?php
namespace PHPWord\Tests\Section;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord\Tests\TestHelper;

class PageNumberingTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelper::clear();
    }

    public function testSectionPageNumbering()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $section->getSettings()->setPageNumberingStart(2);

        $doc = TestHelper::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        $this->assertEquals(2, $element->getAttribute('w:start'));
    }
}