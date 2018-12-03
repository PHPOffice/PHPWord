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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\TextBox
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\TextBox
 * @runTestsInSeparateProcesses
 */
class TextBoxTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oTextBox = new TextBox();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextBox', $oTextBox);
        $this->assertNull($oTextBox->getStyle());
    }

    /**
     * Get style name
     */
    public function testStyleText()
    {
        $oTextBox = new TextBox('textBoxStyle');

        $this->assertEquals('textBoxStyle', $oTextBox->getStyle());
    }

    /**
     * Get style array
     */
    public function testStyleArray()
    {
        $oTextBox = new TextBox(
            array(
                'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(4.5),
                'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(17.5),
                'positioning' => 'absolute',
                'marginLeft'  => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.4),
                'marginTop'   => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(9.9),
                'stroke'      => 0,
                'innerMargin' => 0,
                'borderSize'  => 1,
                'borderColor' => '',
            )
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\TextBox', $oTextBox->getStyle());
    }
}
