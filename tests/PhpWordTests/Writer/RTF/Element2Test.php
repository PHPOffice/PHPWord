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

namespace PhpOffice\PhpWordTests\Writer\RTF;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Element\TextRun as WriterTextRun;
use PhpOffice\PhpWord\Writer\RTF\Element\Title as WriterTitle;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Element subnamespace.
 */
class Element2Test extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /** @param WriterTextRun|WriterTitle $field */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    public function testTextRun(): void
    {
        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\TextRun();
        $element->addText('Hello ');
        $element->addText('there.');
        $textrun = new WriterTextRun($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\ql{{\\cf0\\f0 Hello }{\\cf0\\f0 there.}}\\par\n";
        self::assertEquals($expect, $this->removeCr($textrun));
    }

    public function testTextRunParagraphStyle(): void
    {
        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\TextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
        $element->addText('Hello ');
        $element->addText('there.');
        $textrun = new WriterTextRun($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\ql\\sb0\\sa0{{\\cf0\\f0 Hello }{\\cf0\\f0 there.}}\\par\n";
        self::assertEquals($expect, $this->removeCr($textrun));
    }

    public function testTitle(): void
    {
        $parentWriter = new RTF();
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        Settings::setDefaultRtl(false);
        $phpWord->addTitleStyle(1, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $section = $phpWord->addSection();
        $element = $section->addTitle('First Heading', 1);
        $elwrite = new WriterTitle($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\ql\\sb0\\sa0{\\outlinelevel0{\\cf0\\f0 First Heading}\\par\n}";
        self::assertEquals($expect, $this->removeCr($elwrite));
        Settings::setDefaultRtl(null);
    }
}
