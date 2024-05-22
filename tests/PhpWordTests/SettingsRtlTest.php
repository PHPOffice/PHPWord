<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\TextDirection;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Settings.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Settings
 *
 * @runTestsInSeparateProcesses
 */
class SettingsRtlTest extends TestCase
{
    /** @var bool */
    private $defaultRtl;

    protected function setUp(): void
    {
        $this->defaultRtl = Settings::isDefaultRtl();
    }

    protected function tearDown(): void
    {
        Settings::setDefaultRtl($this->defaultRtl);
    }

    public function testSetGetDefaultRtl(): void
    {
        self::assertNull(Settings::isDefaultRtl());
        Settings::setDefaultRtl(true);
        self::assertTrue(Settings::isDefaultRtl());
        Settings::setDefaultRtl(false);
        self::assertFalse(Settings::isDefaultRtl());
        Settings::setDefaultRtl(null);
        self::assertNull(Settings::isDefaultRtl());
    }

    public function testNormalStyleAdded(): void
    {
        $phpWord = new PhpWord();
        self::assertNull(Settings::isDefaultRtl());
        Settings::setDefaultRtl(true);
        $style = Style::getStyle('Normal');
        self::assertInstanceOf(Font::class, $style);
        self::assertTrue($style->isRtl());
        $paragraph = $style->getParagraph();
        self::assertTrue($paragraph->isBidi());
        self::assertSame(TextDirection::RLTB, $paragraph->getTextDirection());
    }

    public function testNormalStyleNotReplaced(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle([]);
        $style = Style::getStyle('Normal');
        self::assertInstanceOf(Paragraph::class, $style);
        self::assertNotTrue($style->isBidi());
        self::assertSame(TextDirection::NONE, $style->getTextDirection());
    }
}
