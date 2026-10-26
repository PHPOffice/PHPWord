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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class ImageSvgTest extends \PHPUnit\Framework\TestCase
{
    private const CX = '6772275';
    private const CY = '3648075';

    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testStyleDimensionsZero(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $svg = $section->addImage(
            'samples/resources/sample.svg'
        );
        $svg->getStyle()->setWidth(0);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $path = '/w:document/w:body/w:p[1]/w:r[1]/w:drawing/wp:inline/wp:extent';
        self::assertTrue($doc->elementExists($path));
        self::assertSame(
            self::CX,
            $doc->getElementAttribute($path, 'cx')
        );
        self::assertSame(
            self::CY,
            $doc->getElementAttribute($path, 'cy')
        );
    }

    public function testNoStyle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $svg = $section->addImage(
            'samples/resources/sample.svg'
        );
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $path = '/w:document/w:body/w:p[1]/w:r[1]/w:drawing/wp:inline/wp:extent';
        self::assertTrue($doc->elementExists($path));
        self::assertSame(
            self::CX,
            $doc->getElementAttribute($path, 'cx')
        );
        self::assertSame(
            self::CY,
            $doc->getElementAttribute($path, 'cy')
        );
    }
}
