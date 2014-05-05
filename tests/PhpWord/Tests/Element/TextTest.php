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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\Text
 *
 * @runTestsInSeparateProcesses
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $oText = new Text();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $oText);
        $this->assertEquals(null, $oText->getText());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }

    /**
     * Get text
     */
    public function testText()
    {
        $oText = new Text('text');

        $this->assertEquals($oText->getText(), 'text');
    }

    /**
     * Get font style
     */
    public function testFont()
    {
        $oText = new Text('text', 'fontStyle');
        $this->assertEquals($oText->getFontStyle(), 'fontStyle');

        $oText->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
    }

    /**
     * Get font style as object
     */
    public function testFontObject()
    {
        $font = new Font();
        $oText = new Text('text', $font);
        $this->assertEquals($oText->getFontStyle(), $font);
    }

    /**
     * Get paragraph style
     */
    public function testParagraph()
    {
        $oText = new Text('text', 'fontStyle', 'paragraphStyle');
        $this->assertEquals($oText->getParagraphStyle(), 'paragraphStyle');

        $oText->setParagraphStyle(array('align' => 'center', 'spaceAfter' => 100));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }
}
