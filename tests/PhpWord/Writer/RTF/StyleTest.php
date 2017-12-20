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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF;

use PhpOffice\PhpWord\Writer\RTF\Style\Border;

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
        $styles = array('Font', 'Paragraph', 'Section');
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
}
