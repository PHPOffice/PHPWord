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

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

/**
 * Test class for PhpOffice\PhpWord\Element\Text.
 *
 * @runTestsInSeparateProcesses
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $oText = new Text();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $oText);
        self::assertNull($oText->getText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }

    /**
     * Get text.
     */
    public function testText(): void
    {
        $oText = new Text('text');

        self::assertEquals('text', $oText->getText());
    }

    /**
     * Get font style.
     */
    public function testFont(): void
    {
        $oText = new Text('text', 'fontStyle');
        self::assertEquals('fontStyle', $oText->getFontStyle());

        $oText->setFontStyle(['bold' => true, 'italic' => true, 'size' => 16]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
    }

    /**
     * Get font style as object.
     */
    public function testFontObject(): void
    {
        $font = new Font();
        $oText = new Text('text', $font);
        self::assertEquals($font, $oText->getFontStyle());
    }

    /**
     * Get paragraph style.
     */
    public function testParagraph(): void
    {
        $oText = new Text('text', 'fontStyle', 'paragraphStyle');
        self::assertEquals('paragraphStyle', $oText->getParagraphStyle());

        $oText->setParagraphStyle(['alignment' => Jc::CENTER, 'spaceAfter' => 100]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }
}
