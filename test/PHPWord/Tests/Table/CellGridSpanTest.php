<?php
namespace PHPWord\Tests\Table;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord\Tests\TestHelper;

class CellGridSpanTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelper::clear();
    }

    public function testCellGridSpan()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $table = $section->addTable();

        $table->addRow();
        $cell = $table->addCell(200);
        $cell->getStyle()->setGridSpan(5);

        $table->addRow();
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);

        $doc = TestHelper::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:gridSpan');

        $this->assertEquals(5, $element->getAttribute('w:val'));
    }
}