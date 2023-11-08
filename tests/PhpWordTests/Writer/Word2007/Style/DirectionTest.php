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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html as SharedHtml;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class DirectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles.
     */
    public function testDirection(): void
    {
        $word = new PhpWord();
        Settings::setDefaultRtl(true);
        $section = $word->addSection();
        $html = '<p>  الألم الذي ربما تنجم عنه بعض ا.</p>';
        SharedHtml::addHtml($section, $html, false, false);
        $english = '<p style="text-align: left; direction: ltr;">LTR in RTL document.</p>';
        SharedHtml::addHtml($section, $english, false, false);
        $doc = TestHelperDOCX::getDocument($word, 'Word2007');

        $path = '/w:document/w:body/w:p[1]/w:pPr/w:bidi';
        self::assertTrue($doc->elementExists($path));
        $path = '/w:document/w:body/w:p[2]/w:pPr/w:bidi';
        self::assertFalse($doc->elementExists($path));

        $path = '/w:document/w:body/w:p[1]/w:pPr/w:jc';
        self::assertFalse($doc->elementExists($path));
        $path = '/w:document/w:body/w:p[2]/w:pPr/w:jc';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('start', $doc->getElementAttribute($path, 'w:val'));
    }
}
