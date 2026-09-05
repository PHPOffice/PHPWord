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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Border as BorderType;
use PhpOffice\PhpWord\Style\Border as BorderStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Border as BorderWriter;
use PHPUnit\Framework\TestCase;

class BorderTest extends TestCase
{
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

        $expect = '';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderSize(1);
        $expect = '\brdrt\brdrs\brdrw1\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw1\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw1\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw1\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border not all all four sides.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderSide(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setBorderLeftSize(1);
        $expect = '\brdrl\brdrs\brdrw1\brsp80 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderBottomSize(100);
        $expect = '\brdrl\brdrs\brdrw1\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw100\brsp20 ';
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

        $style->setBorderSize(1);

        $writer->setType('section');
        $expect = '\pgbrdropt32';
        $expect .= '\pgbrdrt\brdrs\brdrw1\brsp480 ';
        $expect .= '\pgbrdrl\brdrs\brdrw1\brsp480 ';
        $expect .= '\pgbrdrr\brdrs\brdrw1\brsp480 ';
        $expect .= '\pgbrdrb\brdrs\brdrw1\brsp480 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('paragraph');
        $expect = '\brdrt\brdrs\brdrw1\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw1\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw1\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw1\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('font');
        $expect = '\chbrdr\brdrs\brdrw1 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('row');
        $expect = '\trbrdrt\brdrs\brdrw1 ';
        $expect .= '\trbrdrl\brdrs\brdrw1 ';
        $expect .= '\trbrdrr\brdrs\brdrw1 ';
        $expect .= '\trbrdrb\brdrs\brdrw1 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs\brdrw1 ';
        $expect .= '\clbrdrl\brdrs\brdrw1 ';
        $expect .= '\clbrdrr\brdrs\brdrw1 ';
        $expect .= '\clbrdrb\brdrs\brdrw1 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border style.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     */
    public function testBorderStyle(): void
    {
        $parentWriter = new RTF();
        $style = new BorderStyle();
        $writer = new BorderWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setBorderStyle(BorderType::DASH_DOT_STROKED);
        $expect = '\brdrt\brdrdashdotstr\brsp20 ';
        $expect .= '\brdrl\brdrdashdotstr\brsp80 ';
        $expect .= '\brdrr\brdrdashdotstr\brsp80 ';
        $expect .= '\brdrb\brdrdashdotstr\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::DASHED);
        $style->setBorderLeftStyle(BorderType::DASH_SMALL_GAP);
        $style->setBorderRightStyle(BorderType::DOT_DASH);
        $style->setBorderBottomStyle(BorderType::DOT_DOT_DASH);
        $expect = '\brdrt\brdrdash\brsp20 ';
        $expect .= '\brdrl\brdrdashsm\brsp80 ';
        $expect .= '\brdrr\brdrdashd\brsp80 ';
        $expect .= '\brdrb\brdrdashdd\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::DOTTED);
        $style->setBorderLeftStyle(BorderType::DOUBLE);
        $style->setBorderRightStyle(BorderType::DOUBLE_WAVE);
        $style->setBorderBottomStyle(BorderType::INSET);
        $expect = '\brdrt\brdrdot\brsp20 ';
        $expect .= '\brdrl\brdrdb\brsp80 ';
        $expect .= '\brdrr\brdrwavydb\brsp80 ';
        $expect .= '\brdrb\brdrinset\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::NIL);
        $style->setBorderLeftStyle(BorderType::NONE);
        $style->setBorderRightStyle(BorderType::OUTSET);
        $style->setBorderBottomStyle(BorderType::THICK);
        $expect = '\brdrt\brdrnil\brsp20 ';
        $expect .= '\brdrl\brdrnone\brsp80 ';
        $expect .= '\brdrr\brdroutset\brsp80 ';
        $expect .= '\brdrb\brdrth\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THICK_THIN_LARGE_GAP);
        $style->setBorderLeftStyle(BorderType::THICK_THIN_MEDIUM_GAP);
        $style->setBorderRightStyle(BorderType::THICK_THIN_SMALL_GAP);
        $style->setBorderBottomStyle(BorderType::THIN_THICK_LARGE_GAP);
        $expect = '\brdrt\brdrtnthlg\brsp20 ';
        $expect .= '\brdrl\brdrtnthmg\brsp80 ';
        $expect .= '\brdrr\brdrtnthsg\brsp80 ';
        $expect .= '\brdrb\brdrthtnlg\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THIN_THICK_MEDIUM_GAP);
        $style->setBorderLeftStyle(BorderType::THIN_THICK_SMALL_GAP);
        $style->setBorderRightStyle(BorderType::THIN_THICK_THIN_LARGE_GAP);
        $style->setBorderBottomStyle(BorderType::THIN_THICK_THIN_MEDIUM_GAP);
        $expect = '\brdrt\brdrthtnmg\brsp20 ';
        $expect .= '\brdrl\brdrthtnsg\brsp80 ';
        $expect .= '\brdrr\brdrtnthtnlg\brsp80 ';
        $expect .= '\brdrb\brdrtnthtnmg\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THIN_THICK_THIN_SMALL_GAP);
        $style->setBorderLeftStyle(BorderType::THREE_D_EMBOSS);
        $style->setBorderRightStyle(BorderType::THREE_D_ENGRAVE);
        $style->setBorderBottomStyle(BorderType::TRIPLE);
        $expect = '\brdrt\brdrtnthtnsg\brsp20 ';
        $expect .= '\brdrl\brdremboss\brsp80 ';
        $expect .= '\brdrr\brdrengrave\brsp80 ';
        $expect .= '\brdrb\brdrtriple\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderStyle(BorderType::WAVE);
        $expect = '\brdrt\brdrwavy\brsp20 ';
        $expect .= '\brdrl\brdrwavy\brsp80 ';
        $expect .= '\brdrr\brdrwavy\brsp80 ';
        $expect .= '\brdrb\brdrwavy\brsp20 ';
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
        $expect = '\brdrt\brdrs\brdrw100\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw100\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw100\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw100\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopSize(200);
        $style->setBorderLeftSize(150);
        $style->setBorderRightSize(50);
        $style->setBorderBottomSize(20);
        $expect = '\brdrt\brdrs\brdrw200\brsp20 ';
        $expect .= '\brdrl\brdrs\brdrw150\brsp80 ';
        $expect .= '\brdrr\brdrs\brdrw50\brsp80 ';
        $expect .= '\brdrb\brdrs\brdrw20\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test Border colors.
     * See page 89-90 of RTF Specification 1.9.1 for Paragraph Borders.
     *
     * Create test when paragraph inherits border.
     */

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
        $expect = '\brdrt\brdrs\brsp100 ';
        $expect .= '\brdrl\brdrs\brsp100 ';
        $expect .= '\brdrr\brdrs\brsp100 ';
        $expect .= '\brdrb\brdrs\brsp100 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopSpace(200);
        $style->setBorderLeftSpace(150);
        $style->setBorderRightSpace(50);
        $style->setBorderBottomSpace(20);
        $expect = '\brdrt\brdrs\brsp200 ';
        $expect .= '\brdrl\brdrs\brsp150 ';
        $expect .= '\brdrr\brdrs\brsp50 ';
        $expect .= '\brdrb\brdrs\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Space doesn't matter for fonts.
        $writer->setType('font');
        $expect = '';
        self::assertEquals($expect, $this->removeCr($writer));

        // Space doesn't matter for rows.
        $writer->setType('row');
        $expect = '';
        self::assertEquals($expect, $this->removeCr($writer));

        // Space doesn't matter for cells.
        $writer->setType('cell');
        $expect = '';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    public function testBorderColor(): void
    {
        $phpWord = new PhpWord();

        $paragraphStyleName = 'P-Style';
        $pstyle = $phpWord->addParagraphStyle($paragraphStyleName, [
            'spaceAfter' => 95,
            'borderTopSize' => 12,
            'borderTopColor' => 'FF0000',
            'borderBottomSize' => 12,
            'borderBottomColor' => '00FF00',
            'borderLeftSize' => 12,
            'borderLeftColor' => '0000FF',
            'borderRightSize' => 12,
            'borderRightColor' => 'FFFF00',
        ]);

        $section = $phpWord->addSection();
        $section->addText('Hello', null, $pstyle);
        $section->addText('Goodbye');

        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '{\colortbl;\red0\green0\blue0;\red255\green0\blue0;\red0\green0\blue255;\red255\green255\blue0;\red0\green255\blue0;}';
        self::assertStringContainsString($expected, $content);
        $expected = '\pard\sa95\widctlpar\brdrt\brdrs\brdrw12\brdrcf2\brsp20 \brdrl\brdrs\brdrw12\brdrcf3\brsp80 \brdrr\brdrs\brdrw12\brdrcf4\brsp80 \brdrb\brdrs\brdrw12\brdrcf5\brsp20  {Hello}\par';
        self::assertStringContainsString($expected, $content);
    }
}
