<?php
namespace PHPWord\Tests;

use PHPWord_Settings;

/**
 * Class TOCTest
 *
 * @package PHPWord\Tests
 * @covers  PHPWord_Settings
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPWord_Settings::setCompatibility
     * @covers PHPWord_Settings::getCompatibility
     */
    public function testGetSetCompatibility()
    {
        $this->assertTrue(PHPWord_Settings::getCompatibility());
        $this->assertTrue(PHPWord_Settings::setCompatibility(false));
        $this->assertFalse(PHPWord_Settings::getCompatibility());
        $this->assertFalse(PHPWord_Settings::setCompatibility('Non boolean'));
    }
}
