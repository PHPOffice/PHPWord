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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Fill.
 */
class FillTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * A shape declares filled and fillcolor; on belongs to v:stroke, v:shadow and o:extrusion.
     */
    public function testFillOfShape(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addShape('rect', [
            'frame' => ['width' => 100, 'height' => 100],
            'fill' => ['color' => '#FF0000'],
        ]);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $shape = '/w:document/w:body/w:p/w:r/w:pict/v:rect';
        self::assertTrue($doc->elementExists($shape));

        $attributes = [];
        foreach ($doc->getElement($shape)->attributes as $attribute) {
            $attributes[] = $attribute->nodeName;
        }
        sort($attributes);
        self::assertSame(['fillcolor', 'filled', 'style'], $attributes);

        self::assertSame('t', $doc->getElementAttribute($shape, 'filled'));
        self::assertSame('#FF0000', $doc->getElementAttribute($shape, 'fillcolor'));
    }
}
