<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Style;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Style
 * @runTestsInSeparateProcesses
 */
class StyleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::addParagraphStyle
     * @covers ::addFontStyle
     * @covers ::addLinkStyle
     * @covers ::addTitleStyle
     */
    public function testStyles()
    {
        $paragraph = array('align' => 'center');
        $font = array('italic' => true);
        $table = array('bgColor' => 'CCCCCC');
        $styles = array('Paragraph' => 'Paragraph', 'Font' => 'Font',
            'Link' => 'Font', 'Table' => 'Table',
            'Heading_1' => 'Font', 'Normal' => 'Paragraph');
        $elementCount = 6;
        Style::addParagraphStyle('Paragraph', $paragraph);
        Style::addFontStyle('Font', $font);
        Style::addLinkStyle('Link', $font);
        Style::addTableStyle('Table', $table);
        Style::addTitleStyle(1, $font);
        Style::setDefaultParagraphStyle($paragraph);

        $this->assertEquals($elementCount, count(Style::getStyles()));
        foreach ($styles as $name => $style) {
            $this->assertInstanceOf("PhpOffice\\PhpWord\\Style\\{$style}", Style::getStyle($name));
        }
        $this->assertNull(Style::getStyle('Unknown'));
    }
}
