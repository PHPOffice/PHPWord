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
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Frame.
 */
class FrameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * The wrapping types of ST_WrapType; inline is not one of them.
     */
    private const WRAP_TYPES = ['topAndBottom', 'square', 'none', 'tight', 'through'];

    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * An inline shape is in line with the text and has no wrap; a floating one is wrapped by the text.
     */
    public function testWrapOfShape(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $source = __DIR__ . '/../../../_files/images/earth.jpg';

        $section->addImage($source, ['width' => 40, 'height' => 40]);
        $section->addImage($source, [
            'width' => 40,
            'height' => 40,
            'positioning' => 'absolute',
            'wrappingStyle' => 'square',
        ]);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $shape = '/w:document/w:body/w:p[1]/w:r/w:pict/v:shape';
        self::assertFalse($doc->elementExists($shape . '/w10:wrap'));

        $wrap = '/w:document/w:body/w:p[2]/w:r/w:pict/v:shape/w10:wrap';
        self::assertTrue($doc->elementExists($wrap));
        self::assertContains($doc->getElementAttribute($wrap, 'type'), self::WRAP_TYPES);
        self::assertSame('square', $doc->getElementAttribute($wrap, 'type'));
    }
}
