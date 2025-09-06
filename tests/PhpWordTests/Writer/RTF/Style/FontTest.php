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

namespace PhpOffice\PhpWordTests\Writer\RTF\Style;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style\Font.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\RTF\Style\Font
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * Test basic font settings.
     */
    public function testFontBasics(): void
    {
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setName('Times New Roman');
        $font->setSize(22);
        $font->setColor('yellow');
        // $font->setHint('test');

        $writer = new RTF\Style\Font($font);
        $writer->setParentWriter(new RTF());
        $result = $writer->write();

        self::assertEquals('\f0\fs44\cf0 ', $result);
    }

    /**
     * Test font style settings.
     */
    public function testFontStyle(): void
    {
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setBold(true);
        $font->setItalic(true);
        $font->setUnderline('dashLong');
        $font->setStrikethrough(true);
        $font->setDoubleStrikethrough(true); // cancels out strike
        $font->setSuperScript(true);
        $font->setSubScript(true); // cancels out super
        $font->setSmallCaps(true);
        $font->setAllCaps(true); // cancels out smallcaps
        $font->setFgColor('yellow');
        $font->setBgColor('yellow');
        $font->setHidden(true);

        $writer = new RTF\Style\Font($font);
        $writer->setParentWriter(new RTF());
        $result = $writer->write();

        self::assertEquals('\b\i\ulldash\striked1\sub\caps\highlight0\v\cb0 ', $result);
    }

    /**
     * Test font spacing settings.
     */
    public function testFontSpacing(): void
    {
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setScale(5);
        $font->setSpacing(4);
        $font->setKerning(100);
        $font->setPosition(10);

        $writer = new RTF\Style\Font($font);
        $writer->setParentWriter(new RTF());
        $result = $writer->write();

        self::assertEquals('\charscalex5\expnd4\kerning200\up10 ', $result);
    }

    /**
     * Test general font settings.
     */
    public function testFontGeneral(): void
    {
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setRTL(true);
        $font->setNoProof(true);

        $writer = new RTF\Style\Font($font);
        $writer->setParentWriter(new RTF());
        $result = $writer->write();

        self::assertEquals('\rtlch\noproof ', $result);
    }
}
