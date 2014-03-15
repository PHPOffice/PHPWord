<?php
namespace PHPWord\Tests;

use PHPWord_Style;

/**
 * Class StyleTest
 *
 * @package PHPWord\Tests
 * @covers  PHPWord_Style
 * @runTestsInSeparateProcesses
 */
class StyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPWord_Style::addParagraphStyle
     * @covers PHPWord_Style::addFontStyle
     * @covers PHPWord_Style::addLinkStyle
     * @covers PHPWord_Style::addTitleStyle
     */
    public function testStyles()
    {
        $paragraph = array('align' => 'center');
        $font = array('italic' => true);
        $table = array('bgColor' => 'CCCCCC');
        $styles = array('Paragraph' => 'Paragraph', 'Font' => 'Font',
            'Link' => 'Font', 'Table' => 'TableFull',
            'Heading_1' => 'Font', 'Normal' => 'Paragraph');
        $elementCount = 6;
        PHPWord_Style::addParagraphStyle('Paragraph', $paragraph);
        PHPWord_Style::addFontStyle('Font', $font);
        PHPWord_Style::addLinkStyle('Link', $font);
        PHPWord_Style::addTableStyle('Table', $table);
        PHPWord_Style::addTitleStyle(1, $font);
        PHPWord_Style::setDefaultParagraphStyle($paragraph);

        $this->assertEquals($elementCount, count(PHPWord_Style::getStyles()));
        foreach ($styles as $name => $style) {
            $expected = "PHPWord_Style_{$style}";
            $this->assertInstanceOf($expected, PHPWord_Style::getStyle($name));
        }
        $this->assertNull(PHPWord_Style::getStyle('Unknown'));
    }
}
