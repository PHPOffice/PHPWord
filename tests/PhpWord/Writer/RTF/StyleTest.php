<?php
declare(strict_types=1);
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

use PhpOffice\PhpWord\Style\BorderSide;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Border;
use PHPUnit\Framework\Assert;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
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
        $border->setBorder('top', new BorderSide(
            Absolute::from('pt', 1),
            new Hex('FF0000'),
            null,
            Absolute::from('twip', 20)
        ));
        $border->setBorder('left', new BorderSide(
            Absolute::from('pt', 1),
            new Hex('FF0000'),
            null,
            Absolute::from('twip', 20)
        ));
        $border->setBorder('right', new BorderSide(
            Absolute::from('pt', 1),
            new Hex('FF0000'),
            null,
            Absolute::from('twip', 20)
        ));
        $border->setBorder('bottom', new BorderSide(
            Absolute::from('pt', 1),
            new Hex('FF0000'),
            null,
            Absolute::from('twip', 20)
        ));

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
        $indentation->setLeft(Absolute::from('twip', 1));
        $indentation->setRight(Absolute::from('twip', 2));
        $indentation->setFirstLine(Absolute::from('twip', 3));

        $indentWriter = new \PhpOffice\PhpWord\Writer\RTF\Style\Indentation($indentation);
        $indentWriter->setParentWriter(new RTF());
        $result = $indentWriter->write();

        Assert::assertEquals('\fi3\li1\ri2 ', $result);
    }

    public function testRightTab()
    {
        $tabRight = new \PhpOffice\PhpWord\Style\Tab();
        $tabRight->setType(\PhpOffice\PhpWord\Style\Tab::TAB_STOP_RIGHT);
        $tabRight->setPosition(Absolute::from('twip', 5));

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
}
