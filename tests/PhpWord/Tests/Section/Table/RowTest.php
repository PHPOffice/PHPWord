<?php
namespace PhpOffice\PhpWord\Tests\Section\Table;

use PhpOffice\PhpWord\Section\Table\Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oRow = new Row('section', $iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table\\Row', $oRow);
        $this->assertEquals($oRow->getHeight(), null);
        $this->assertInternalType('array', $oRow->getCells());
        $this->assertCount(0, $oRow->getCells());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    public function testConstructWithParams()
    {
        $iVal = rand(1, 1000);
        $iVal2 = rand(1, 1000);
        $oRow = new Row(
            'section',
            $iVal,
            $iVal2,
            array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF')
        );

        $this->assertEquals($oRow->getHeight(), $iVal2);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    public function testAddCell()
    {
        $oRow = new Row('section', 1);
        $element = $oRow->addCell();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table\\Cell', $element);
        $this->assertCount(1, $oRow->getCells());
    }
}
