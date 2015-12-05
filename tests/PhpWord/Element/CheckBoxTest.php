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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\CheckBox
 *
 * @runTestsInSeparateProcesses
 */
class CheckBoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $oCheckBox = new CheckBox();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\CheckBox', $oCheckBox);
        $this->assertNull($oCheckBox->getText());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }

    /**
     * Get name and text
     */
    public function testCheckBox()
    {
        $oCheckBox = new CheckBox(htmlspecialchars('chkBox', ENT_COMPAT, 'UTF-8'), htmlspecialchars('CheckBox', ENT_COMPAT, 'UTF-8'));

        $this->assertEquals(htmlspecialchars('chkBox', ENT_COMPAT, 'UTF-8'), $oCheckBox->getName());
        $this->assertEquals(htmlspecialchars('CheckBox', ENT_COMPAT, 'UTF-8'), $oCheckBox->getText());
    }

    /**
     * Get font style
     */
    public function testFont()
    {
        $oCheckBox = new CheckBox(htmlspecialchars('chkBox', ENT_COMPAT, 'UTF-8'), htmlspecialchars('CheckBox', ENT_COMPAT, 'UTF-8'), 'fontStyle');
        $this->assertEquals('fontStyle', $oCheckBox->getFontStyle());

        $oCheckBox->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
    }

    /**
     * Font style as object
     */
    public function testFontObject()
    {
        $font = new Font();
        $oCheckBox = new CheckBox(htmlspecialchars('chkBox', ENT_COMPAT, 'UTF-8'), htmlspecialchars('CheckBox', ENT_COMPAT, 'UTF-8'), $font);
        $this->assertEquals($font, $oCheckBox->getFontStyle());
    }

    /**
     * Get paragraph style
     */
    public function testParagraph()
    {
        $oCheckBox = new CheckBox(htmlspecialchars('chkBox', ENT_COMPAT, 'UTF-8'), htmlspecialchars('CheckBox', ENT_COMPAT, 'UTF-8'), 'fontStyle', 'paragraphStyle');
        $this->assertEquals('paragraphStyle', $oCheckBox->getParagraphStyle());

        $oCheckBox->setParagraphStyle(array('alignment' => Jc::CENTER, 'spaceAfter' => 100));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }
}
