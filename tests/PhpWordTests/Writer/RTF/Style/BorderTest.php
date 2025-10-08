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
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
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

        $expect = '\brdrt\brdrs\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrcf0\brsp20 ';
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
        $expect = '\pgbrdrt\brdrs\brdrcf0\brsp480 ';
        $expect .= '\pgbrdrl\brdrs\brdrcf0\brsp480 ';
        $expect .= '\pgbrdrr\brdrs\brdrcf0\brsp480 ';
        $expect .= '\pgbrdrb\brdrs\brdrcf0\brsp480 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('paragraph');
        $expect = '\brdrt\brdrs\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('font');
        $expect = '\chbrdr\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('row');
        $expect = '\tdbrdrt\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrl\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrr\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs\brdrcf0 ';
        $expect .= '\clbrdrl\brdrs\brdrcf0 ';
        $expect .= '\clbrdrr\brdrs\brdrcf0 ';
        $expect .= '\clbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border size.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderSize(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setBorderSize(100);
        $expect = '\brdrt\brdrs\brdrw100\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw100\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw100\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw100\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopSize(200);
        $style->setBorderLeftSize(150);
        $style->setBorderRightSize(50);
        $style->setBorderBottomSize(20);
        $expect = '\brdrt\brdrs\brdrw200\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw150\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw50\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw20\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border colors.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderColor(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $parentWriter = new RTF($phpWord);

        $style1 = new ParagraphStyle();
        $style1->setBorderColor('FFFFFF');
        $phpWord->addParagraphStyle('style1', $style1);

        $style2 = new ParagraphStyle();
        $style2->setBorderTopColor('FFFF00');
        $style2->setBorderLeftColor('FF0000');
        $style2->setBorderRightColor('000000');
        $style2->setBorderBottomColor('0000FF');
        $phpWord->addParagraphStyle('style2', $style2);

        $parentWriter->getWriterPart('Header')->write();

        $writer = new BorderWriter(new Border($style1));
        $writer->setParentWriter($parentWriter);
        $expect = '\brdrt\brdrs\brdrcf1\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrcf1\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrcf1\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrcf1\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer = new BorderWriter(new Border($style2));
        $writer->setParentWriter($parentWriter);
        $expect = '\brdrt\brdrs\brdrcf2\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrcf3\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrcf4\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrcf5\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border space.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderSpace(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setBorderSpace(100);
        $expect = '\brdrt\brdrs\brdrcf0\brsp100 ';
        $expect .= '\brdrl\brdrs\brdrcf0\brsp100 ';
        $expect .= '\brdrr\brdrs\brdrcf0\brsp100 ';
        $expect .= '\brdrb\brdrs\brdrcf0\brsp100 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopSpace(200);
        $style->setBorderLeftSpace(150);
        $style->setBorderRightSpace(50);
        $style->setBorderBottomSpace(20);
        $expect = '\brdrt\brdrs\brdrcf0\brsp200 ';
        $expect .= '\brdrl\brdrs\brdrcf0\brsp150 ';
        $expect .= '\brdrr\brdrs\brdrcf0\brsp500 ';
        $expect .= '\brdrb\brdrs\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('font');
        $expect = '\chbrdr\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Technically rows can have \brspN, but it messes up the table drawing - margin/padding should be used instead.
        $writer->setType('row');
        $expect = '\tdbrdrt\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrl\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrr\brdrs\brdrcf0 ';
        $expect .= '\tdbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Technically cells can have \brspN, but it messes up the table drawing - margin/padding should be used instead.
        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs\brdrcf0 ';
        $expect .= '\clbrdrl\brdrs\brdrcf0 ';
        $expect .= '\clbrdrr\brdrs\brdrcf0 ';
        $expect .= '\clbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
