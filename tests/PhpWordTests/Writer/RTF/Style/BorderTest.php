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
    public function testBorderBasic(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $expect = '\brdrt\brdrs\brsp20 ';
        $expect .= '\brdrl\brdrs\brsp80 ';
        $expect .= '\brdrr\brdrs\brsp80 ';
        $expect .= '\brdrb\brdrs\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border styles in paragraph.
     * See page 76 of RTF Specification 1.9.1 for Page Borders.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     * See page 103 of RTF Specification 1.9.1 for Row Borders and Cell Borders.
     * See page 139 of RTF Specification 1.9.1 for Character Borders.
     */
    public function testBorderType(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $writer->setType('section');
        $expect = '\pgbrdrt\brdrs\brsp480 ';
        $expect .= '\pgbrdrl\brdrs\brsp480 ';
        $expect .= '\pgbrdrr\brdrs\brsp480 ';
        $expect .= '\pgbrdrb\brdrs\brsp480 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('paragraph');
        $expect = '\brdrt\brdrs\brsp20 ';
        $expect .= '\brdrl\brdrs\brsp80 ';
        $expect .= '\brdrr\brdrs\brsp80 ';
        $expect .= '\brdrb\brdrs\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('font');
        $expect = '\chbrdr\brdrs ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('row');
        $expect = '\tdbrdrt\brdrs ';
        $expect .= '\tdbrdrl\brdrs ';
        $expect .= '\tdbrdrr\brdrs ';
        $expect .= '\tdbrdrb\brdrs ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs ';
        $expect .= '\clbrdrl\brdrs ';
        $expect .= '\clbrdrr\brdrs ';
        $expect .= '\clbrdrb\brdrs ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
