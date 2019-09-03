<?php
declare(strict_types=1);
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

use PhpOffice\PhpWord\Style\Lengths\Absolute;

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
                'width'       => Absolute::from('cm', 4.5),
                'height'      => Absolute::from('cm', 17.5),
                'positioning' => 'absolute',
                'marginLeft'  => Absolute::from('cm', 15.4),
                'marginTop'   => Absolute::from('cm', 9.9),
                'innerMargin' => Absolute::from('eop', 0),
                'borderSize'  => Absolute::from('eop', 1),
            )
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\TextBox', $oTextBox->getStyle());
    }
}
