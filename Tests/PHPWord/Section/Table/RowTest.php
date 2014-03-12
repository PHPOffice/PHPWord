<?php
namespace PHPWord\Tests\Section\Table;

use PHPWord_Section_Table_Row;

class RowTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oRow = new PHPWord_Section_Table_Row('section', $iVal);

        $this->assertInstanceOf('PHPWord_Section_Table_Row', $oRow);
        $this->assertEquals($oRow->getHeight(), null);
        $this->assertInternalType('array', $oRow->getCells());
        $this->assertCount(0, $oRow->getCells());
        $this->assertInstanceOf('PHPWord_Style_Row', $oRow->getStyle());
    }

    public function testConstructWithParams()
    {
        $iVal = rand(1, 1000);
        $iVal2 = rand(1, 1000);
        $oRow = new PHPWord_Section_Table_Row(
            'section',
            $iVal,
            $iVal2,
            array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF')
        );

        $this->assertEquals($oRow->getHeight(), $iVal2);
        $this->assertInstanceOf('PHPWord_Style_Row', $oRow->getStyle());
    }

    public function testAddCell()
    {
        $oRow = new PHPWord_Section_Table_Row('section', 1);
        $element = $oRow->addCell();

        $this->assertInstanceOf('PHPWord_Section_Table_Cell', $element);
        $this->assertCount(1, $oRow->getCells());
    }
}
