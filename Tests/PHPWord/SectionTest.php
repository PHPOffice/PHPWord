<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Section;

/**
 * @covers PHPWord_Section
 */
class SectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPWord_Section::getSettings
     */
    public function testGetSettings()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getSettings(), '_settings', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getElements
     */
    public function testGetElements()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getElements(), '_elementCollection', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getFooter
     */
    public function testGetFooter()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getFooter(), '_footer', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getHeaders
     */
    public function testGetHeaders()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getHeaders(), '_headers', new PHPWord_Section(0));
    }

    public function testGetElements()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getElements(), '_elementCollection', new PHPWord_Section(0));
    }
}
