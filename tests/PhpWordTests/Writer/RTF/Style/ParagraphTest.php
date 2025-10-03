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

namespace PhpOffice\PhpWordTests\Writer\RTF\Styke;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWord\Settings;
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
     * See page 79 of RTF Specification 1.9.1.
     */
    public function testParagraphAlign(): void
    {
        $parentWriter = new RTF();
        $style = new ParagraphStyle()->setAlignment(Jc::START);
        $writer = new ParagraphWriter($parentWriter, $style);
        $expect = "\\pard\\ql ";
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
