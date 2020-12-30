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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF;

use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Border;
use PHPUnit\Framework\Assert;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    public function removeCr($field)
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test empty styles
     */
    public function testEmptyStyles()
    {
        $styles = array('Font', 'Paragraph', 'Section', 'Tab', 'Indentation');
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\RTF\\Style\\' . $style;
            $object = new $objectClass();

            $this->assertEquals('', $object->write());
        }
    }

    public function testBorderWithNonRegisteredColors()
    {
        $border = new Border();
        $border->setSizes(array(1, 2, 3, 4));
        $border->setColors(array('#FF0000', '#FF0000', '#FF0000', '#FF0000'));
        $border->setSizes(array(20, 20, 20, 20));

        $content = $border->write();

        $expected = '\pgbrdropt32';
        $expected .= '\pgbrdrt\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrl\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrr\brdrs\brdrw20\brdrcf0\brsp480 ';
        $expected .= '\pgbrdrb\brdrs\brdrw20\brdrcf0\brsp480 ';

        $this->assertEquals($expected, $content);
    }

    public function testIndentation()
    {
        $indentation = new \PhpOffice\PhpWord\Style\Indentation();
        $indentation->setLeft(1);
        $indentation->setRight(2);
        $indentation->setFirstLine(3);

        $indentWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Indentation($indentation);
        $indentWriter->setParentWriter(new RTF());
        $result = $indentWriter->write();

        Assert::assertEquals('\fi3\li1\ri2 ', $result);
    }

    public function testRightTab()
    {
        $tabRight = new \PhpOffice\PhpWord\Style\Tab();
        $tabRight->setType(\PhpOffice\PhpWord\Style\Tab::TAB_STOP_RIGHT);
        $tabRight->setPosition(5);

        $tabWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Tab($tabRight);
        $tabWriter->setParentWriter(new RTF());
        $result = $tabWriter->write();

        Assert::assertEquals('\tqr\tx5', $result);
    }

    public function testCenterTab()
    {
        $tabRight = new \PhpOffice\PhpWord\Style\Tab();
        $tabRight->setType(\PhpOffice\PhpWord\Style\Tab::TAB_STOP_CENTER);

        $tabWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Tab($tabRight);
        $tabWriter->setParentWriter(new RTF());
        $result = $tabWriter->write();

        Assert::assertEquals('\tqc\tx0', $result);
    }

    public function testDecimalTab()
    {
        $tabRight = new \PhpOffice\PhpWord\Style\Tab();
        $tabRight->setType(\PhpOffice\PhpWord\Style\Tab::TAB_STOP_DECIMAL);

        $tabWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Tab($tabRight);
        $tabWriter->setParentWriter(new RTF());
        $result = $tabWriter->write();

        Assert::assertEquals('\tqdec\tx0', $result);
    }

    public function testRTL()
    {
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('אב גד', array('RTL'=> true));
        $text = new \PhpOffice\PhpWord\Writer\RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar {\\rtlch\\cf0\\f0 \\uc0{\\u1488}\\uc0{\\u1489} \\uc0{\\u1490}\\uc0{\\u1491}}\\par\n";
        $this->assertEquals($expect, $this->removeCr($text));
    }

    public function testPageBreakLineHeight()
    {
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('New page', null, array('lineHeight' => 1.08, 'pageBreakBefore' => true));
        $text = new \PhpOffice\PhpWord\Writer\RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\sl259\\slmult1\\page{\\cf0\\f0 New page}\\par\n";
        $this->assertEquals($expect, $this->removeCr($text));
    }

    public function testPageNumberRestart()
    {
        //$parentWriter = new RTF();
        $phpword = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpword->addSection(array('pageNumberingStart' => 5));
        $styleWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Section($section->getStyle());
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
        $this->assertEquals($expect, $wstyle);
    }
}
