<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oTable = new Table('section', 1);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table', $oTable);
        $this->assertEquals($oTable->getStyle(), null);
        $this->assertEquals($oTable->getWidth(), null);
        $this->assertEquals($oTable->getRows(), array());
        $this->assertCount(0, $oTable->getRows());
    }

    public function testStyleText()
    {
        $oTable = new Table('section', 1, 'tableStyle');

        $this->assertEquals($oTable->getStyle(), 'tableStyle');
    }

    public function testStyleArray()
    {
        $oTable = new Table(
            'section',
            1,
            array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80)
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $oTable->getStyle());
    }

    public function testWidth()
    {
        $oTable = new Table('section', 1);
        $iVal = rand(1, 1000);
        $oTable->setWidth($iVal);
        $this->assertEquals($oTable->getWidth(), $iVal);
    }

    public function testRow()
    {
        $oTable = new Table('section', 1);
        $element = $oTable->addRow();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table\\Row', $element);
        $this->assertCount(1, $oTable->getRows());
    }

    public function testCell()
    {
        $oTable = new Table('section', 1);
        $oTable->addRow();
        $element = $oTable->addCell();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table\\Cell', $element);
    }
}
