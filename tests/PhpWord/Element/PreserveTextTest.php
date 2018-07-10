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

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Test class for PhpOffice\PhpWord\Element\PreserveText
 *
 * @runTestsInSeparateProcesses
 */
class PreserveTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oPreserveText = new PreserveText();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $oPreserveText);
        $this->assertNull($oPreserveText->getText());
        $this->assertNull($oPreserveText->getFontStyle());
        $this->assertNull($oPreserveText->getParagraphStyle());
    }

    /**
     * Create new instance with style name
     */
    public function testConstructWithString()
    {
        $oPreserveText = new PreserveText('text', 'styleFont', 'styleParagraph');
        $this->assertEquals(array('text'), $oPreserveText->getText());
        $this->assertEquals('styleFont', $oPreserveText->getFontStyle());
        $this->assertEquals('styleParagraph', $oPreserveText->getParagraphStyle());
    }

    /**
     * Create new instance with array
     */
    public function testConstructWithArray()
    {
        $oPreserveText = new PreserveText('text', array('size' => 16, 'color' => '1B2232'), array('alignment' => Jc::CENTER));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oPreserveText->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oPreserveText->getParagraphStyle());
    }
}
