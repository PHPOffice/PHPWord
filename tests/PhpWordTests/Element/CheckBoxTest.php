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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\CheckBox;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\CheckBox.
 *
 * @runTestsInSeparateProcesses
 */
class CheckBoxTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct.
     */
    public function testConstruct(): void
    {
        $oCheckBox = new CheckBox();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\CheckBox', $oCheckBox);
        self::assertNull($oCheckBox->getText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }

    /**
     * Get name and text.
     */
    public function testCheckBox(): void
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox');

        self::assertEquals('chkBox', $oCheckBox->getName());
        self::assertEquals('CheckBox', $oCheckBox->getText());
    }

    /**
     * Get font style.
     */
    public function testFont(): void
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', 'fontStyle');
        self::assertEquals('fontStyle', $oCheckBox->getFontStyle());

        $oCheckBox->setFontStyle(['bold' => true, 'italic' => true, 'size' => 16]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oCheckBox->getFontStyle());
    }

    /**
     * Font style as object.
     */
    public function testFontObject(): void
    {
        $font = new Font();
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', $font);
        self::assertEquals($font, $oCheckBox->getFontStyle());
    }

    /**
     * Get paragraph style.
     */
    public function testParagraph(): void
    {
        $oCheckBox = new CheckBox('chkBox', 'CheckBox', 'fontStyle', 'paragraphStyle');
        self::assertEquals('paragraphStyle', $oCheckBox->getParagraphStyle());

        $oCheckBox->setParagraphStyle(['alignment' => Jc::CENTER, 'spaceAfter' => 100]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oCheckBox->getParagraphStyle());
    }
}
