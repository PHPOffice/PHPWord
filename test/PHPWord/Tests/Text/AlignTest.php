<?php
namespace PHPWord\Tests\Text;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord\Tests\TestHelper;

class AlignTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelper::clear();
    }

    public function testAlign()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $section->addText('This is my text', null, array('align' => 'right'));

        $doc = TestHelper::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:jc');

        $this->assertEquals('right', $element->getAttribute('w:val'));
    }
}