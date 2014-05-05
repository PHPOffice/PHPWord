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

use PhpOffice\PhpWord\Element\CheckBox;
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
        $this->assertEquals(null, $oCheckBox->getText());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }

    /**
     * Get name and text
     */
    public function testCheckBox()
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox');

        $this->assertEquals($oCheckBox->getName(), 'chkBox');
        $this->assertEquals($oCheckBox->getText(), 'CheckBox');
    }

    /**
     * Get font style
     */
    public function testFont()
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', 'fontStyle');
        $this->assertEquals($oCheckBox->getFontStyle(), 'fontStyle');

        $oCheckBox->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
    }

    /**
     * Font style as object
     */
    public function testFontObject()
    {
        $font = new Font();
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', $font);
        $this->assertEquals($oCheckBox->getFontStyle(), $font);
    }

    /**
     * Get paragraph style
     */
    public function testParagraph()
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', 'fontStyle', 'paragraphStyle');
        $this->assertEquals($oCheckBox->getParagraphStyle(), 'paragraphStyle');

        $oCheckBox->setParagraphStyle(array('align' => 'center', 'spaceAfter' => 100));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }
}
