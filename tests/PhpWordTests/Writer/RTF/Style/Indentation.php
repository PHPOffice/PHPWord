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
use PhpOffice\PhpWord\Style\Indentation as IndentationStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Indentation as IndentationWriter;
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
     * Test indentation.
     * See page 79 of RTF Specification 1.9.1 for Indentation.
     */
    public function testIndentation(): void
    {
        $parentWriter = new RTF();
        $style = new IndentationStyle();
        $writer = new IndentationWriter($style);
        $writer->setParentWriter($parentWriter);

        $expect = ' ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeft(1440);
        $style->setRight(720);
        $style->setFirstLine(360);
        $style->setFirstLineChars(180);
        $expect = '\fi360\cufi180\li1440\ri720 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style = new IndentationStyle();
        $writer = new IndentationWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setLeft(1440);
        $style->setRight(720);
        $style->setHanging(360);
        $expect = '\fi-360\li1440\ri720 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
