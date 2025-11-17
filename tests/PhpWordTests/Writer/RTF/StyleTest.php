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

use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Border;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    public function removeCr($field)
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test empty styles.
     */
    public function testEmptyStyles(): void
    {
        $styles = ['Font', 'Paragraph', 'Section', 'Tab', 'Indentation'];
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\RTF\\Style\\' . $style;
            $object = new $objectClass();

            self::assertEquals('', $object->write());
        }
    }

    public function testBorderWithNonRegisteredColors(): void
    {
        $border = new Border();
        $border->setSizes([1, 2, 3, 4]);
        $border->setColors(['#FF0000', '#FF0000', '#FF0000', '#FF0000']);
        $border->setSizes([20, 20, 20, 20]);

        $content = $border->write();

        $expected = '\pgbrdropt32';
        $expected .= '\pgbrdrt\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrl\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrr\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrb\brdrs\brdrw20\brdrcf0\brsp480 ';

        self::assertEquals($expected, $content);
    }

    public function testIndentation(): void
    {
        $indentation = new \PhpOffice\PhpWord\Style\Indentation();
        $indentation->setLeft(1);
        $indentation->setRight(2);
        $indentation->setFirstLine(3);

        $indentWriter = new RTF\Style\Indentation($indentation);
        $indentWriter->setParentWriter(new RTF());
        $result = $indentWriter->write();

        self::assertSame('\fi3\li1\ri2 ', $result);
    }

    public function testPageBreakLineHeight(): void
    {
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('New page', null, ['lineHeight' => 1.08, 'pageBreakBefore' => true]);
        $text = new RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\sl259\\slmult1\\page{\\cf0\\f0 New page}\\par\n";
        self::assertEquals($expect, $this->removeCr($text));
    }

    public function testPageNumberRestart(): void
    {
        //$parentWriter = new RTF();
        $phpword = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpword->addSection(['pageNumberingStart' => 5]);
        $styleWriter = new RTF\Style\Section($section->getStyle());
        $wstyle = $this->removeCr($styleWriter);
        // following have default values which might change so don't use them
        $wstyle = preg_replace('/\\\\pgwsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\pghsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\margtsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\margrsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\margbsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\marglsxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\headery\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\footery\\d+/', '', $wstyle);
        $wstyle = preg_replace('/\\\\guttersxn\\d+/', '', $wstyle);
        $wstyle = preg_replace('/  +/', ' ', $wstyle);
        $expect = "\\sectd \\pgnstarts5\\pgnrestart \n";
        self::assertEquals($expect, $wstyle);
    }
}
