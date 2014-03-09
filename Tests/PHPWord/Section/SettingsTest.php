<?php
namespace PHPWord\Tests\Section;

use PHPUnit_Framework_TestCase;
use PHPWord_Section_Settings;

class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testSettingValue()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $oSettings->setSettingValue('_orientation', 'landscape');
        $this->assertEquals($oSettings->getOrientation(), 'landscape');
        $this->assertEquals($oSettings->getPageSizeW(), 16838);
        $this->assertEquals($oSettings->getPageSizeH(), 11906);

        $oSettings->setSettingValue('_orientation', null);
        $this->assertEquals($oSettings->getOrientation(), null);
        $this->assertEquals($oSettings->getPageSizeW(), 11906);
        $this->assertEquals($oSettings->getPageSizeH(), 16838);

        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('_borderSize', $iVal);
        $this->assertEquals($oSettings->getBorderSize(), array($iVal, $iVal, $iVal, $iVal));
        $this->assertEquals($oSettings->getBorderBottomSize(), $iVal);
        $this->assertEquals($oSettings->getBorderLeftSize(), $iVal);
        $this->assertEquals($oSettings->getBorderRightSize(), $iVal);
        $this->assertEquals($oSettings->getBorderTopSize(), $iVal);

        $oSettings->setSettingValue('_borderColor', 'FF00AA');
        $this->assertEquals($oSettings->getBorderColor(), array('FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'));
        $this->assertEquals($oSettings->getBorderBottomColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderLeftColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderRightColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderTopColor(), 'FF00AA');

        $iVal = rand(1, 1000);
        $oSettings->setSettingValue('headerHeight', $iVal);
        $this->assertEquals($oSettings->getHeaderHeight(), $iVal);
    }

    public function testMargin()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $iVal = rand(1, 1000);
        $oSettings->setMarginTop($iVal);
        $this->assertEquals($oSettings->getMarginTop(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setMarginBottom($iVal);
        $this->assertEquals($oSettings->getMarginBottom(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setMarginLeft($iVal);
        $this->assertEquals($oSettings->getMarginLeft(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setMarginRight($iVal);
        $this->assertEquals($oSettings->getMarginRight(), $iVal);
    }

    public function testOrientationLandscape()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $oSettings->setLandscape();
        $this->assertEquals($oSettings->getOrientation(), 'landscape');
        $this->assertEquals($oSettings->getPageSizeW(), 16838);
        $this->assertEquals($oSettings->getPageSizeH(), 11906);
    }

    public function testOrientationPortrait()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $oSettings->setPortrait();
        $this->assertEquals($oSettings->getOrientation(), null);
        $this->assertEquals($oSettings->getPageSizeW(), 11906);
        $this->assertEquals($oSettings->getPageSizeH(), 16838);
    }

    public function testBorderSize()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $iVal = rand(1, 1000);
        $oSettings->setBorderSize($iVal);
        $this->assertEquals($oSettings->getBorderSize(), array($iVal, $iVal, $iVal, $iVal));
        $this->assertEquals($oSettings->getBorderBottomSize(), $iVal);
        $this->assertEquals($oSettings->getBorderLeftSize(), $iVal);
        $this->assertEquals($oSettings->getBorderRightSize(), $iVal);
        $this->assertEquals($oSettings->getBorderTopSize(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setBorderBottomSize($iVal);
        $this->assertEquals($oSettings->getBorderBottomSize(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setBorderLeftSize($iVal);
        $this->assertEquals($oSettings->getBorderLeftSize(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setBorderRightSize($iVal);
        $this->assertEquals($oSettings->getBorderRightSize(), $iVal);

        $iVal = rand(1, 1000);
        $oSettings->setBorderTopSize($iVal);
        $this->assertEquals($oSettings->getBorderTopSize(), $iVal);
    }

    public function testBorderColor()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $oSettings->setBorderColor('FF00AA');
        $this->assertEquals($oSettings->getBorderColor(), array('FF00AA', 'FF00AA', 'FF00AA', 'FF00AA'));
        $this->assertEquals($oSettings->getBorderBottomColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderLeftColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderRightColor(), 'FF00AA');
        $this->assertEquals($oSettings->getBorderTopColor(), 'FF00AA');

        $oSettings->setBorderBottomColor('BBCCDD');
        $this->assertEquals($oSettings->getBorderBottomColor(), 'BBCCDD');

        $oSettings->setBorderLeftColor('CCDDEE');
        $this->assertEquals($oSettings->getBorderLeftColor(), 'CCDDEE');

        $oSettings->setBorderRightColor('11EE22');
        $this->assertEquals($oSettings->getBorderRightColor(), '11EE22');

        $oSettings->setBorderTopColor('22FF33');
        $this->assertEquals($oSettings->getBorderTopColor(), '22FF33');
    }

    public function testNumberingStart()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $this->assertEquals($oSettings->getPageNumberingStart(), null);

        $iVal = rand(1, 1000);
        $oSettings->setPageNumberingStart($iVal);
        $this->assertEquals($oSettings->getPageNumberingStart(), $iVal);

        $oSettings->setPageNumberingStart();
        $this->assertEquals($oSettings->getPageNumberingStart(), null);
    }

    public function testHeader()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $this->assertEquals($oSettings->getHeaderHeight(), 720);

        $iVal = rand(1, 1000);
        $oSettings->setHeaderHeight($iVal);
        $this->assertEquals($oSettings->getHeaderHeight(), $iVal);

        $oSettings->setHeaderHeight();
        $this->assertEquals($oSettings->getHeaderHeight(), 720);
    }

    public function testFooter()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $this->assertEquals($oSettings->getFooterHeight(), 720);

        $iVal = rand(1, 1000);
        $oSettings->setFooterHeight($iVal);
        $this->assertEquals($oSettings->getFooterHeight(), $iVal);

        $oSettings->setFooterHeight();
        $this->assertEquals($oSettings->getFooterHeight(), 720);
    }

    public function testColumnsNum()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        // Default
        $this->assertEquals($oSettings->getColsNum(), 1);

        $iVal = rand(1, 1000);
        $oSettings->setColsNum($iVal);
        $this->assertEquals($oSettings->getColsNum(), $iVal);

        $oSettings->setColsNum();
        $this->assertEquals($oSettings->getColsNum(), 1);
    }

    public function testColumnsSpace()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        // Default
        $this->assertEquals($oSettings->getColsSpace(), 720);

        $iVal = rand(1, 1000);
        $this->assertInstanceOf('PHPWord_Section_Settings', $oSettings->setColsSpace($iVal));
        $this->assertEquals($oSettings->getColsSpace(), $iVal);

        $this->assertInstanceOf('PHPWord_Section_Settings', $oSettings->setColsSpace());
        $this->assertEquals($oSettings->getColsSpace(), 1);
    }

    public function testBreakType()
    {
        // Section Settings
        $oSettings = new PHPWord_Section_Settings();

        $this->assertEquals($oSettings->getBreakType(), null);

        $oSettings->setBreakType('continuous');
        $this->assertEquals($oSettings->getBreakType(), 'continuous');

        $oSettings->setBreakType();
        $this->assertEquals($oSettings->getBreakType(), null);
    }
}