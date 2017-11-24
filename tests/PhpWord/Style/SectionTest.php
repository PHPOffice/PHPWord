<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\Section
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Section
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testSettingValue()
    {
        $oSettings = new Section();

        $this->assertEquals('portrait', $oSettings->getOrientation());
        $this->assertEquals(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), '', 0.000000001);
        $this->assertEquals(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), '', 0.000000001);
        $this->assertEquals('A4', $oSettings->getPaperSize());

        $oSettings->setSettingValue('orientation', 'landscape');
        $this->assertEquals('landscape', $oSettings->getOrientation());
        $this->assertEquals(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeW(), '', 0.000000001);
        $this->assertEquals(Section::DEFAULT_WIDTH, $oSettings->getPageSizeH(), '', 0.000000001);

        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('borderSize', $iVal);
        $this->assertEquals(array($iVal, $iVal, $iVal, $iVal), $oSettings->getBorderSize());
        $this->assertEquals($iVal, $oSettings->getBorderBottomSize());
        $this->assertEquals($iVal, $oSettings->getBorderLeftSize());
        $this->assertEquals($iVal, $oSettings->getBorderRightSize());
        $this->assertEquals($iVal, $oSettings->getBorderTopSize());

        $oSettings->setSettingValue('borderColor', 'FF00AA');
        $this->assertEquals(array('FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'), $oSettings->getBorderColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderBottomColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderLeftColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderRightColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderTopColor());

        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('headerHeight', $iVal);
        $this->assertEquals($iVal, $oSettings->getHeaderHeight());

        $oSettings->setSettingValue('lineNumbering', array());
        $oSettings->setSettingValue(
            'lineNumbering',
            array(
                'start'     => 1,
                'increment' => 1,
                'distance'  => 240,
                'restart'   => 'newPage',
            )
        );
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\LineNumbering', $oSettings->getLineNumbering());

        $oSettings->setSettingValue('lineNumbering', null);
        $this->assertNull($oSettings->getLineNumbering());
    }

    /**
     * Set/get margin
     */
    public function testMargin()
    {
        // Section Settings
        $oSettings = new Section();

        $iVal = rand(1, 1000);
        $oSettings->setMarginTop($iVal);
        $this->assertEquals($iVal, $oSettings->getMarginTop());

        $iVal = rand(1, 1000);
        $oSettings->setMarginBottom($iVal);
        $this->assertEquals($iVal, $oSettings->getMarginBottom());

        $iVal = rand(1, 1000);
        $oSettings->setMarginLeft($iVal);
        $this->assertEquals($iVal, $oSettings->getMarginLeft());

        $iVal = rand(1, 1000);
        $oSettings->setMarginRight($iVal);
        $this->assertEquals($iVal, $oSettings->getMarginRight());
    }

    /**
     * Set/get page width
     */
    public function testPageWidth()
    {
        // Section Settings
        $oSettings = new Section();

        $this->assertEquals(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), '', 0.000000001);
        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('pageSizeW', $iVal);
        $this->assertEquals($iVal, $oSettings->getPageSizeW());
    }

    /**
     * Set/get page height
     */
    public function testPageHeight()
    {
        // Section Settings
        $oSettings = new Section();

        $this->assertEquals(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), '', 0.000000001);
        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('pageSizeH', $iVal);
        $this->assertEquals($iVal, $oSettings->getPageSizeH());
    }

    /**
     * Set/get landscape orientation
     */
    public function testOrientationLandscape()
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setLandscape();
        $this->assertEquals('landscape', $oSettings->getOrientation());
        $this->assertEquals(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeW(), '', 0.000000001);
        $this->assertEquals(Section::DEFAULT_WIDTH, $oSettings->getPageSizeH(), '', 0.000000001);
    }

    /**
     * Set/get portrait orientation
     */
    public function testOrientationPortrait()
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setPortrait();
        $this->assertEquals('portrait', $oSettings->getOrientation());
        $this->assertEquals(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), '', 0.000000001);
        $this->assertEquals(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), '', 0.000000001);
    }

    /**
     * Set/get border size
     */
    public function testBorderSize()
    {
        // Section Settings
        $oSettings = new Section();

        $iVal = rand(1, 1000);
        $oSettings->setBorderSize($iVal);
        $this->assertEquals(array($iVal, $iVal, $iVal, $iVal), $oSettings->getBorderSize());
        $this->assertEquals($iVal, $oSettings->getBorderBottomSize());
        $this->assertEquals($iVal, $oSettings->getBorderLeftSize());
        $this->assertEquals($iVal, $oSettings->getBorderRightSize());
        $this->assertEquals($iVal, $oSettings->getBorderTopSize());

        $iVal = rand(1, 1000);
        $oSettings->setBorderBottomSize($iVal);
        $this->assertEquals($iVal, $oSettings->getBorderBottomSize());

        $iVal = rand(1, 1000);
        $oSettings->setBorderLeftSize($iVal);
        $this->assertEquals($iVal, $oSettings->getBorderLeftSize());

        $iVal = rand(1, 1000);
        $oSettings->setBorderRightSize($iVal);
        $this->assertEquals($iVal, $oSettings->getBorderRightSize());

        $iVal = rand(1, 1000);
        $oSettings->setBorderTopSize($iVal);
        $this->assertEquals($iVal, $oSettings->getBorderTopSize());
    }

    /**
     * Set/get border color
     */
    public function testBorderColor()
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setBorderColor('FF00AA');
        $this->assertEquals(array('FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'), $oSettings->getBorderColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderBottomColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderLeftColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderRightColor());
        $this->assertEquals('FF00AA', $oSettings->getBorderTopColor());

        $oSettings->setBorderBottomColor('BBCCDD');
        $this->assertEquals('BBCCDD', $oSettings->getBorderBottomColor());

        $oSettings->setBorderLeftColor('CCDDEE');
        $this->assertEquals('CCDDEE', $oSettings->getBorderLeftColor());

        $oSettings->setBorderRightColor('11EE22');
        $this->assertEquals('11EE22', $oSettings->getBorderRightColor());

        $oSettings->setBorderTopColor('22FF33');
        $this->assertEquals('22FF33', $oSettings->getBorderTopColor());
    }

    /**
     * Set/get page numbering start
     */
    public function testNumberingStart()
    {
        // Section Settings
        $oSettings = new Section();

        $this->assertNull($oSettings->getPageNumberingStart());

        $iVal = rand(1, 1000);
        $oSettings->setPageNumberingStart($iVal);
        $this->assertEquals($iVal, $oSettings->getPageNumberingStart());

        $oSettings->setPageNumberingStart();
        $this->assertNull($oSettings->getPageNumberingStart());
    }

    /**
     * Set/get header height
     */
    public function testHeader()
    {
        $oSettings = new Section();

        $this->assertEquals(720, $oSettings->getHeaderHeight());

        $iVal = rand(1, 1000);
        $oSettings->setHeaderHeight($iVal);
        $this->assertEquals($iVal, $oSettings->getHeaderHeight());

        $oSettings->setHeaderHeight();
        $this->assertEquals(720, $oSettings->getHeaderHeight());
    }

    /**
     * Set/get footer height
     */
    public function testFooter()
    {
        // Section Settings
        $oSettings = new Section();

        $this->assertEquals(720, $oSettings->getFooterHeight());

        $iVal = rand(1, 1000);
        $oSettings->setFooterHeight($iVal);
        $this->assertEquals($iVal, $oSettings->getFooterHeight());

        $oSettings->setFooterHeight();
        $this->assertEquals(720, $oSettings->getFooterHeight());
    }

    /**
     * Set/get column number
     */
    public function testColumnsNum()
    {
        // Section Settings
        $oSettings = new Section();

        // Default
        $this->assertEquals(1, $oSettings->getColsNum());

        // Null value
        $oSettings->setColsNum();
        $this->assertEquals(1, $oSettings->getColsNum());

        // Random value
        $iVal = rand(1, 1000);
        $oSettings->setColsNum($iVal);
        $this->assertEquals($iVal, $oSettings->getColsNum());
    }

    /**
     * Set/get column spacing
     */
    public function testColumnsSpace()
    {
        // Section Settings
        $oSettings = new Section();

        // Default
        $this->assertEquals(720, $oSettings->getColsSpace());

        $iVal = rand(1, 1000);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $oSettings->setColsSpace($iVal));
        $this->assertEquals($iVal, $oSettings->getColsSpace());

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $oSettings->setColsSpace());
        $this->assertEquals(720, $oSettings->getColsSpace());
    }

    /**
     * Set/get break type
     */
    public function testBreakType()
    {
        // Section Settings
        $oSettings = new Section();

        $this->assertNull($oSettings->getBreakType());

        $oSettings->setBreakType('continuous');
        $this->assertEquals('continuous', $oSettings->getBreakType());

        $oSettings->setBreakType();
        $this->assertNull($oSettings->getBreakType());
    }
}
