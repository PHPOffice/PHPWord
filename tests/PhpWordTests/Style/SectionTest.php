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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Section;

/**
 * Test class for PhpOffice\PhpWord\Style\Section.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Section
 *
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    public function testSettingValue(): void
    {
        $oSettings = new Section();

        self::assertEquals('portrait', $oSettings->getOrientation());
        self::assertEqualsWithDelta(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), 0.000000001);
        self::assertEqualsWithDelta(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), 0.000000001);
        self::assertEquals('A4', $oSettings->getPaperSize());

        $oSettings->setSettingValue('orientation', 'landscape');
        self::assertEquals('landscape', $oSettings->getOrientation());
        self::assertEqualsWithDelta(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeW(), 0.000000001);
        self::assertEqualsWithDelta(Section::DEFAULT_WIDTH, $oSettings->getPageSizeH(), 0.000000001);

        $iVal = mt_rand(1, 1000);
        $oSettings->setSettingValue('borderSize', $iVal);
        self::assertEquals([$iVal, $iVal, $iVal, $iVal], $oSettings->getBorderSize());
        self::assertEquals($iVal, $oSettings->getBorderBottomSize());
        self::assertEquals($iVal, $oSettings->getBorderLeftSize());
        self::assertEquals($iVal, $oSettings->getBorderRightSize());
        self::assertEquals($iVal, $oSettings->getBorderTopSize());

        $oSettings->setSettingValue('borderColor', 'FF00AA');
        self::assertEquals(['FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'], $oSettings->getBorderColor());
        self::assertEquals('FF00AA', $oSettings->getBorderBottomColor());
        self::assertEquals('FF00AA', $oSettings->getBorderLeftColor());
        self::assertEquals('FF00AA', $oSettings->getBorderRightColor());
        self::assertEquals('FF00AA', $oSettings->getBorderTopColor());

        $iVal = mt_rand(1, 1000);
        $oSettings->setSettingValue('headerHeight', $iVal);
        self::assertEquals($iVal, $oSettings->getHeaderHeight());

        $oSettings->setSettingValue('lineNumbering', []);
        $oSettings->setSettingValue(
            'lineNumbering',
            [
                'start' => 1,
                'increment' => 1,
                'distance' => 240,
                'restart' => 'newPage',
            ]
        );
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\LineNumbering', $oSettings->getLineNumbering());

        $oSettings->setSettingValue('lineNumbering', null);
        self::assertNull($oSettings->getLineNumbering());
    }

    /**
     * Set/get margin.
     */
    public function testMargin(): void
    {
        // Section Settings
        $oSettings = new Section();

        $iVal = mt_rand(1, 1000);
        $oSettings->setMarginTop($iVal);
        self::assertEquals($iVal, $oSettings->getMarginTop());

        $iVal = mt_rand(1, 1000);
        $oSettings->setMarginBottom($iVal);
        self::assertEquals($iVal, $oSettings->getMarginBottom());

        $iVal = mt_rand(1, 1000);
        $oSettings->setMarginLeft($iVal);
        self::assertEquals($iVal, $oSettings->getMarginLeft());

        $iVal = mt_rand(1, 1000);
        $oSettings->setMarginRight($iVal);
        self::assertEquals($iVal, $oSettings->getMarginRight());
    }

    /**
     * Set/get page width.
     */
    public function testPageWidth(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertEqualsWithDelta(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), 0.000000001);
        $iVal = mt_rand(1, 1000);
        $oSettings->setSettingValue('pageSizeW', $iVal);
        self::assertEquals($iVal, $oSettings->getPageSizeW());
    }

    /**
     * Set/get page height.
     */
    public function testPageHeight(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertEqualsWithDelta(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), 0.000000001);
        $iVal = mt_rand(1, 1000);
        $oSettings->setSettingValue('pageSizeH', $iVal);
        self::assertEquals($iVal, $oSettings->getPageSizeH());
    }

    /**
     * Set/get landscape orientation.
     */
    public function testOrientationLandscape(): void
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setLandscape();
        self::assertEquals('landscape', $oSettings->getOrientation());
        self::assertEqualsWithDelta(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeW(), 0.000000001);
        self::assertEqualsWithDelta(Section::DEFAULT_WIDTH, $oSettings->getPageSizeH(), 0.000000001);
    }

    /**
     * Set/get portrait orientation.
     */
    public function testOrientationPortrait(): void
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setPortrait();
        self::assertEquals('portrait', $oSettings->getOrientation());
        self::assertEqualsWithDelta(Section::DEFAULT_WIDTH, $oSettings->getPageSizeW(), 0.000000001);
        self::assertEqualsWithDelta(Section::DEFAULT_HEIGHT, $oSettings->getPageSizeH(), 0.000000001);
    }

    /**
     * Set/get border size.
     */
    public function testBorderSize(): void
    {
        // Section Settings
        $oSettings = new Section();

        $iVal = mt_rand(1, 1000);
        $oSettings->setBorderSize($iVal);
        self::assertEquals([$iVal, $iVal, $iVal, $iVal], $oSettings->getBorderSize());
        self::assertEquals($iVal, $oSettings->getBorderBottomSize());
        self::assertEquals($iVal, $oSettings->getBorderLeftSize());
        self::assertEquals($iVal, $oSettings->getBorderRightSize());
        self::assertEquals($iVal, $oSettings->getBorderTopSize());

        $iVal = mt_rand(1, 1000);
        $oSettings->setBorderBottomSize($iVal);
        self::assertEquals($iVal, $oSettings->getBorderBottomSize());

        $iVal = mt_rand(1, 1000);
        $oSettings->setBorderLeftSize($iVal);
        self::assertEquals($iVal, $oSettings->getBorderLeftSize());

        $iVal = mt_rand(1, 1000);
        $oSettings->setBorderRightSize($iVal);
        self::assertEquals($iVal, $oSettings->getBorderRightSize());

        $iVal = mt_rand(1, 1000);
        $oSettings->setBorderTopSize($iVal);
        self::assertEquals($iVal, $oSettings->getBorderTopSize());
    }

    /**
     * Set/get border color.
     */
    public function testBorderColor(): void
    {
        // Section Settings
        $oSettings = new Section();

        $oSettings->setBorderColor('FF00AA');
        self::assertEquals(['FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'], $oSettings->getBorderColor());
        self::assertEquals('FF00AA', $oSettings->getBorderBottomColor());
        self::assertEquals('FF00AA', $oSettings->getBorderLeftColor());
        self::assertEquals('FF00AA', $oSettings->getBorderRightColor());
        self::assertEquals('FF00AA', $oSettings->getBorderTopColor());

        $oSettings->setBorderBottomColor('BBCCDD');
        self::assertEquals('BBCCDD', $oSettings->getBorderBottomColor());

        $oSettings->setBorderLeftColor('CCDDEE');
        self::assertEquals('CCDDEE', $oSettings->getBorderLeftColor());

        $oSettings->setBorderRightColor('11EE22');
        self::assertEquals('11EE22', $oSettings->getBorderRightColor());

        $oSettings->setBorderTopColor('22FF33');
        self::assertEquals('22FF33', $oSettings->getBorderTopColor());
    }

    /**
     * Set/get page numbering start.
     */
    public function testNumberingStart(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertNull($oSettings->getPageNumberingStart());

        $iVal = mt_rand(1, 1000);
        $oSettings->setPageNumberingStart($iVal);
        self::assertEquals($iVal, $oSettings->getPageNumberingStart());

        $oSettings->setPageNumberingStart();
        self::assertNull($oSettings->getPageNumberingStart());
    }

    /**
     * Set/get header height.
     */
    public function testHeader(): void
    {
        $oSettings = new Section();

        self::assertEquals(720, $oSettings->getHeaderHeight());

        $iVal = mt_rand(1, 1000);
        $oSettings->setHeaderHeight($iVal);
        self::assertEquals($iVal, $oSettings->getHeaderHeight());

        $oSettings->setHeaderHeight();
        self::assertEquals(720, $oSettings->getHeaderHeight());
    }

    /**
     * Set/get footer height.
     */
    public function testFooter(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertEquals(720, $oSettings->getFooterHeight());

        $iVal = mt_rand(1, 1000);
        $oSettings->setFooterHeight($iVal);
        self::assertEquals($iVal, $oSettings->getFooterHeight());

        $oSettings->setFooterHeight();
        self::assertEquals(720, $oSettings->getFooterHeight());
    }

    /**
     * Set/get column number.
     */
    public function testColumnsNum(): void
    {
        // Section Settings
        $oSettings = new Section();

        // Default
        self::assertEquals(1, $oSettings->getColsNum());

        // Null value
        $oSettings->setColsNum();
        self::assertEquals(1, $oSettings->getColsNum());

        // Random value
        $iVal = mt_rand(1, 1000);
        $oSettings->setColsNum($iVal);
        self::assertEquals($iVal, $oSettings->getColsNum());
    }

    /**
     * Set/get column spacing.
     */
    public function testColumnsSpace(): void
    {
        // Section Settings
        $oSettings = new Section();

        // Default
        self::assertEquals(720, $oSettings->getColsSpace());

        $iVal = mt_rand(1, 1000);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $oSettings->setColsSpace($iVal));
        self::assertEquals($iVal, $oSettings->getColsSpace());

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $oSettings->setColsSpace());
        self::assertEquals(720, $oSettings->getColsSpace());
    }

    /**
     * Set/get break type.
     */
    public function testBreakType(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertNull($oSettings->getBreakType());

        $oSettings->setBreakType('continuous');
        self::assertEquals('continuous', $oSettings->getBreakType());

        $oSettings->setBreakType();
        self::assertNull($oSettings->getBreakType());
    }

    /**
     * Vertical page alignment.
     */
    public function testVerticalAlign(): void
    {
        // Section Settings
        $oSettings = new Section();

        self::assertNull($oSettings->getVAlign());

        $oSettings->setVAlign(VerticalJc::BOTH);
        self::assertEquals('both', $oSettings->getVAlign());
    }
}
