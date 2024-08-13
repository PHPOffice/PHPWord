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

namespace PhpOffice\PhpWordTests\Writer\RTF\Element;

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Border;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Element\Table as WriterTable;

class TableTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    public function removeCr(WriterTable $field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    public function testTable(): void
    {
        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Table();
        $width = 100;
        $width2 = 2 * $width;
        $element->addRow();
        $tce = $element->addCell($width);
        $tce->addText('1');
        $tce = $element->addCell($width);
        $tce->addText('2');
        $element->addRow();
        $tce = $element->addCell($width);
        $tce->addText('3');
        $tce = $element->addCell($width);
        $tce->addText('4');
        $table = new WriterTable($parentWriter, $element);
        $expect = implode("\n", [
            '\\pard',
            "\\trowd \\cellx$width \\cellx$width2 ",
            '\\intbl',
            '\\ql{\\cf0\\f0 1}\\par',
            '\\cell',
            '\\intbl',
            '{\\cf0\\f0 2}\\par',
            '\\cell',
            '\\row',
            "\\trowd \\cellx$width \\cellx$width2 ",
            '\\intbl',
            '\\ql{\\cf0\\f0 3}\\par',
            '\\cell',
            '\\intbl',
            '{\\cf0\\f0 4}\par',
            '\\cell',
            '\\row',
            '\\pard',
            '',
        ]);

        self::assertEquals($expect, $this->removeCr($table));
    }

    public function testTableStyle(): void
    {
        $width = 100;

        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();

        Style::addTableStyle('TableStyle', ['borderSize' => 6, 'borderColor' => '006699']);

        $element = new Table('TableStyle');
        $element->addRow();
        $elementCell = $element->addCell($width);
        $elementCell->addText('1');

        $expect = implode("\n", [
            '\\pard',
            '\\trowd \\clbrdrt\\brdrs\\brdrw2\\brdrcf0',
            '\\clbrdrl\\brdrs\\brdrw2\\brdrcf0',
            '\\clbrdrb\\brdrs\\brdrw2\\brdrcf0',
            '\\clbrdrr\\brdrs\\brdrw2\\brdrcf0',
            "\\cellx$width ",
            '\\intbl',
            '\\ql{\\cf0\\f0 1}\\par',
            '\\cell',
            '\\row',
            '\\pard',
            '',
        ]);

        self::assertEquals($expect, $this->removeCr(new WriterTable($parentWriter, $element)));
    }

    public function testTableStyleNotExisting(): void
    {
        $width = 100;

        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();

        $element = new Table('TableStyleNotExisting');
        $element->addRow();
        $elementCell = $element->addCell($width);
        $elementCell->addText('1');

        $expect = implode("\n", [
            '\\pard',
            "\\trowd \\cellx$width ",
            '\\intbl',
            '\\ql{\\cf0\\f0 1}\\par',
            '\\cell',
            '\\row',
            '\\pard',
            '',
        ]);

        self::assertEquals($expect, $this->removeCr(new WriterTable($parentWriter, $element)));
    }

    public function testTableCellStyle(): void
    {
        $width = 100;

        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();

        $element = new Table();
        $element->addRow();
        $elementCell = $element->addCell($width, ['borderSize' => 6, 'borderColor' => '006699', 'borderStyle' => Border::DOTTED]);
        $elementCell->addText('1');

        $expect = implode("\n", [
            '\\pard',
            '\\trowd \\clbrdrt\\brdrdot\\brdrw2\\brdrcf0',
            '\\clbrdrl\\brdrdot\\brdrw2\\brdrcf0',
            '\\clbrdrb\\brdrdot\\brdrw2\\brdrcf0',
            '\\clbrdrr\\brdrdot\\brdrw2\\brdrcf0',
            "\\cellx$width ",
            '\\intbl',
            '\\ql{\\cf0\\f0 1}\\par',
            '\\cell',
            '\\row',
            '\\pard',
            '',
        ]);

        self::assertEquals($expect, $this->removeCr(new WriterTable($parentWriter, $element)));
    }
}
