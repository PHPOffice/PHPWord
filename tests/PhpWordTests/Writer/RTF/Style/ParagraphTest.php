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
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Paragraph as ParagraphWriter;
use PHPUnit\Framework\TestCase;

class ParagraphTest extends TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param ParagraphWriter $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test alignment.
     * See page 79 of RTF Specification 1.9.1 for Alignment.
     * See page 81 of RTF Specification 1.9.1 for Bidirectional Controls.
     */
    public function testParagraphAlign(): void
    {
        $parentWriter = new RTF();
        $style = new ParagraphStyle();
        $writer = new ParagraphWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setAlignment(Jc::START);
        $expect = '\pard\ql\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::CENTER);
        $expect = '\pard\qc\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::END);
        $expect = '\pard\qr\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::BOTH);
        $expect = '\pard\qj\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::DISTRIBUTE);
        $expect = '\pard\qd\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::THAI_DISTRIBUTE);
        $expect = '\pard\qt\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::HIGH_KASHIDA);
        $expect = '\pard\qk20\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::MEDIUM_KASHIDA);
        $expect = '\pard\qk10\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::LOW_KASHIDA);
        $expect = '\pard\qk0\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::START);
        $style->setBidi(true);
        $expect = '\pard\qr\rtlpar\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setAlignment(Jc::END);
        $style->setBidi(true);
        $expect = '\pard\ql\rtlpar\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test indentation.
     * See PHPOFfice\Tests\Writer\RTF\Style\IndentationTest.
     */

    /**
     * Test formatting.
     * See page 78 of RTF Specification 1.9.1 for Formatting.
     */
    public function testParagraphFormatting(): void
    {
        $parentWriter = new RTF();
        $style = new ParagraphStyle();
        $writer = new ParagraphWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setSuppressAutoHyphens(true);
        $style->setKeepLines(true);
        $style->setKeepNext(true);
        $style->setWidowControl(true);
        $style->setPageBreakBefore(true);
        $expect = '\pard\widctlpar\keepn\keep\pagebb\hyphpar0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setSuppressAutoHyphens(false);
        $style->setKeepLines(false);
        $style->setKeepNext(false);
        $style->setWidowControl(false);
        $style->setPageBreakBefore(false);
        $expect = '\pard\nowidctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test spacing.
     * See page 80 of RTF Specification 1.9.1 for Spacing.
     */
    public function testParagraphSpacing(): void
    {
        $parentWriter = new RTF();
        $style = new ParagraphStyle();
        $writer = new ParagraphWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setSpaceBefore(240);
        $style->setSpaceAfter(120);
        $style->setLineHeight(1.5);
        $style->setContextualSpacing(false);
        $expect = '\pard\sb240\sa120\sl360\slmult1\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style = new ParagraphStyle();
        $writer = new ParagraphWriter($style);
        $writer->setParentWriter($parentWriter);
        
        $style->setSpaceBefore(480);
        $style->setSpaceAfter(360);
        $style->setSpacing(30);
        $style->setSpacingLineRule(LineSpacingRule::EXACT);
        $style->setContextualSpacing(true);
        $expect = '\pard\sb480\sa360\sl30\slmult0\contextualspace\widctlpar ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test tabs.
     * See PHPOFfice\Tests\Writer\RTF\Style\TabTest.
     */

    /**
     * Not Done: basedOn, next, numLevel, numStyle, shading, textAlignment.
     */
}
