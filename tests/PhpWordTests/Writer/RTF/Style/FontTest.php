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
use PhpOffice\PhpWord\Element\Text as TextElement;
use PhpOffice\PhpWord\Style\Font as FontStyle;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Element\Text as TextWriter;
use PhpOffice\PhpWord\Writer\RTF\Style\Font as FontWriter;
use PHPUnit\Framework\TestCase;

class FontTest extends TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param FontWriter $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test font and color.
     * See page 131 of RTF Specification 1.9.1 for Font (Character).
     * See page 142 of RTF Specification 1.9.1 for Highlighting.
     */
    public function testFontColor(): void
    {
        $parentWriter = new RTF();
        $style = new FontStyle();
        $writer = new FontWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setName('Times New Roman');
        $style->setFallbackFont('serif');
        $style->setSize(24);
        $style->setColor($style::FGCOLOR_YELLOW);
        $style->setFgColor($style::FGCOLOR_RED);
        $style->setBgColor('#123456');
        $expect = '\f0\fs48\cf0\highlight0\cb0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test font and color after registering header tables.
     */
    public function testFontColorRegistered(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $parentWriter = new RTF($phpWord);
        $style = new FontStyle();
        $element = new TextElement();
        $writer = new TextWriter($parentWriter, $element);

        $style->setName('Times New Roman');
        $style->setFallbackFont('serif');
        $style->setSize(24);
        $style->setColor($style::FGCOLOR_YELLOW);
        $style->setFgColor($style::FGCOLOR_RED);
        $style->setBgColor('#123456');

        $phpWord->addFontStyle('style1', $style);
        $parentWriter->getWriterPart('Header')->write();

        $element->setText('Test');
        $element->setFontStyle($style);

        $expect = '\f0\fs48\cf0\highlight0\cb0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test formatting.
     * See page 130-133 of RTF Specification 1.9.1 for Font (Character).
     */
    public function testFontFormatting(): void
    {
        $parentWriter = new RTF();
        $style = new FontStyle();
        $writer = new FontWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setAllCaps(true);
        $style->setBold(true);
        $style->setdoubleStrikethrough(true);
        $style->setHidden(true);
        $style->setItalic(true);
        $style->setNoProof(true);
        $style->setSubScript(true);
        $expect = '\b\i\striked1\sub\caps\v\noproof\lang1024 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setSmallCaps(true);
        $style->setStrikethrough(true);
        $style->setSuperScript(true);
        $style->setNoProof(false);
        $expect = '\b\i\strike\super\scaps\v ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Disable styles (in case default is enabled)
        $style->setBold(false);
        $style->setHidden(false);
        $style->setItalic(false);
        $style->setSmallCaps(false);
        $style->setStrikethrough(false);
        $style->setSuperScript(false);
        $expect = '\b0\i0\strike0\v0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test underline.
     * See page 132-133 of RTF Specification 1.9.1 for Formatting.
     */
    public function testFontUnderline(): void
    {
        $parentWriter = new RTF();
        $style = new FontStyle();
        $writer = new FontWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setUnderline($style::UNDERLINE_DASH);
        $expect = '\uldash ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DASHHEAVY);
        $expect = '\ulthdash ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DASHLONG);
        $expect = '\ulldash ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DASHLONGHEAVY);
        $expect = '\ulthldash ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOUBLE);
        $expect = '\uldb ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTDASH);
        $expect = '\uldashd ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTDASHHEAVY);
        $expect = '\ulthdashd ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTDOTDASH);
        $expect = '\uldashdd ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTDOTDASHHEAVY);
        $expect = '\ulthdashdd ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTTED);
        $expect = '\uld ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_DOTTEDHEAVY);
        $expect = '\ulthd ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_HEAVY);
        $expect = '\ulth ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_SINGLE);
        $expect = '\ul ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_WAVY);
        $expect = '\ulwave ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_WAVYDOUBLE);
        $expect = '\ululdbwave ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_WAVYHEAVY);
        $expect = '\ulhwave ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_WORDS);
        $expect = '\ulw ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setUnderline($style::UNDERLINE_NONE);
        $expect = '';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test language.
     * See page 132 of RTF Specification 1.9.1 for Spacing.
     */
    public function testFontLang(): void
    {
        $parentWriter = new RTF();
        $style = new FontStyle();
        $writer = new FontWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setRTL(true);
        $style->setLang(Language::KO_KR);
        $expect = '\rtlch\lang1042 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setRTL(false);
        $style->setLang(Language::EN_US);
        $expect = '\ltrch\lang1033 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test font spacing settings.
     */
    public function testFontSpacing(): void
    {
        $parentWriter = new RTF();
        $style = new FontStyle();
        $writer = new FontWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setScale(5);
        $style->setSpacing(4);
        $style->setKerning(100);
        $style->setPosition(10);
        $expect = '\charscalex5\expnd4\kerning200\up10 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
