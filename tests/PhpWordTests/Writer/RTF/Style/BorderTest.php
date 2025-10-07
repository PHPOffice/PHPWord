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
use PhpOffice\PhpWord\SimpleType\Border as BorderType;
use PhpOffice\PhpWord\Style\Border as BorderStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Border as BorderWriter;
use PHPUnit\Framework\TestCase;

class BorderTest extends TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param BorderWriter $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test Border styles in paragraph.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderStyle(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setBorderSize(40);
        $style->setBorderColor('#FF0000');
        $style->setBorderStyle(BorderType::DASHED);
        $style->setBorderSpace(20);

        $expect = '\brdrt\brdrdash\brdrw40\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrdash\brdrw40\brdrcf0\brsp20 ';
        $expect .= '\brdrr\brdrdash\brdrw40\brdrcf0\brsp20 ';
        $expect .= '\brdrb\brdrdash\brdrw40\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
