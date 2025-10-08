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
        $expect = '\pgbrdropt32';
        $expect .= '\pgbrdrt\brdrs\brdrcf0\brsp480 ';
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
        $expect = '\trbrdrt\brdrs\brdrcf0 ';
        $expect .= '\trbrdrl\brdrs\brdrcf0 ';
        $expect .= '\trbrdrr\brdrs\brdrcf0 ';
        $expect .= '\trbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs\brdrcf0 ';
        $expect .= '\clbrdrl\brdrs\brdrcf0 ';
        $expect .= '\clbrdrr\brdrs\brdrcf0 ';
        $expect .= '\clbrdrb\brdrs\brdrcf0 ';
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
        $expect = '\brdrt\brdrdashdotstr\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrdashdotstr\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrdashdotstr\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrdashdotstr\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::DASHED);
        $style->setBorderLeftStyle(BorderType::DASH_SMALL_GAP);
        $style->setBorderRightStyle(BorderType::DOT_DASH);
        $style->setBorderBottomStyle(BorderType::DOT_DOT_DASH);
        $expect = '\brdrt\brdrdash\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrdashsm\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrdashd\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrdashdd\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::DOTTED);
        $style->setBorderLeftStyle(BorderType::DOUBLE);
        $style->setBorderRightStyle(BorderType::DOUBLE_WAVE);
        $style->setBorderBottomStyle(BorderType::INSET);
        $expect = '\brdrt\brdrdot\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrdb\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrwavydb\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrinset\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::NIL);
        $style->setBorderLeftStyle(BorderType::NONE);
        $style->setBorderRightStyle(BorderType::OUTSET);
        $style->setBorderBottomStyle(BorderType::THICK);
        $expect = '\brdrt\brdrnil\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrnone\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdroutset\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrth\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THICK_THIN_LARGE_GAP);
        $style->setBorderLeftStyle(BorderType::THICK_THIN_MEDIUM_GAP);
        $style->setBorderRightStyle(BorderType::THICK_THIN_SMALL_GAP);
        $style->setBorderBottomStyle(BorderType::THIN_THICK_LARGE_GAP);
        $expect = '\brdrt\brdrtnthlg\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrtnthmg\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrtnthsg\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrthtnlg\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THIN_THICK_MEDIUM_GAP);
        $style->setBorderLeftStyle(BorderType::THIN_THICK_SMALL_GAP);
        $style->setBorderRightStyle(BorderType::THIN_THICK_THIN_LARGE_GAP);
        $style->setBorderBottomStyle(BorderType::THIN_THICK_THIN_MEDIUM_GAP);
        $expect = '\brdrt\brdrthtnmg\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrthtnsg\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrtnthtnlg\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrtnthtnmg\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderTopStyle(BorderType::THIN_THICK_THIN_SMALL_GAP);
        $style->setBorderLeftStyle(BorderType::THREE_D_EMBOSS);
        $style->setBorderRightStyle(BorderType::THREE_D_ENGRAVE);
        $style->setBorderBottomStyle(BorderType::TRIPLE);
        $expect = '\brdrt\brdrtnthtnsg\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdremboss\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrengrave\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrtriple\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setBorderStyle(BorderType::WAVE);
        $expect = '\brdrt\brdrwavy\brdrcf0\brsp20 ';
        $expect .= '\brdrl\brdrwavy\brdrcf0\brsp80 ';
        $expect .= '\brdrr\brdrwavy\brdrcf0\brsp80 ';
        $expect .= '\brdrb\brdrwavy\brdrcf0\brsp20 ';
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
        $expect .= '\brdrr\brdrs\brdrcf0\brsp50 ';
        $expect .= '\brdrb\brdrs\brdrcf0\brsp20 ';
        self::assertEquals($expect, $this->removeCr($writer));

        $writer->setType('font');
        $expect = '\chbrdr\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Technically rows can have space, but it messes up the table drawing - margin/padding should be used instead.
        $writer->setType('row');
        $expect = '\trbrdrt\brdrs\brdrcf0 ';
        $expect .= '\trbrdrl\brdrs\brdrcf0 ';
        $expect .= '\trbrdrr\brdrs\brdrcf0 ';
        $expect .= '\trbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));

        // Technically cells can have space, but it messes up the table drawing - margin/padding should be used instead.
        $writer->setType('cell');
        $expect = '\clbrdrt\brdrs\brdrcf0 ';
        $expect .= '\clbrdrl\brdrs\brdrcf0 ';
        $expect .= '\clbrdrr\brdrs\brdrcf0 ';
        $expect .= '\clbrdrb\brdrs\brdrcf0 ';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
