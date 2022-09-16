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

namespace PhpOffice\PhpWordTests\Writer\ODText;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Style subnamespace.
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles.
     */
    public function testEmptyStyles(): void
    {
        $styles = ['Font', 'Paragraph', 'Image', 'Section', 'Table'];
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Style\\' . $style;
            $xmlWriter = new XMLWriter();
            $object = new $objectClass($xmlWriter);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }
}
