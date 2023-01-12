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

use PhpOffice\PhpWord\Element\PreserveText;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Test class for PhpOffice\PhpWord\Element\PreserveText.
 *
 * @runTestsInSeparateProcesses
 */
class PreserveTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance.
     */
    public function testConstruct(): void
    {
        $oPreserveText = new PreserveText();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $oPreserveText);
        self::assertNull($oPreserveText->getText());
        self::assertNull($oPreserveText->getFontStyle());
        self::assertNull($oPreserveText->getParagraphStyle());
    }

    /**
     * Create new instance with style name.
     */
    public function testConstructWithString(): void
    {
        $oPreserveText = new PreserveText('text', 'styleFont', 'styleParagraph');
        self::assertEquals(['text'], $oPreserveText->getText());
        self::assertEquals('styleFont', $oPreserveText->getFontStyle());
        self::assertEquals('styleParagraph', $oPreserveText->getParagraphStyle());
    }

    /**
     * Create new instance with array.
     */
    public function testConstructWithArray(): void
    {
        $oPreserveText = new PreserveText('text', ['size' => 16, 'color' => '1B2232'], ['alignment' => Jc::CENTER]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oPreserveText->getFontStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oPreserveText->getParagraphStyle());
    }
}
