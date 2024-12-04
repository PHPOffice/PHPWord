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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html as SharedHtml;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace.
 */
class DirectionTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * Test unmatched elements.
     */
    public function testDirection(): void
    {
        $doc = new PhpWord();
        Settings::setDefaultRtl(true);
        $section = $doc->addSection();
        $html = '<p>  الألم الذي ربما تنجم عنه بعض ا.</p>';
        SharedHtml::addHtml($section, $html, false, false);
        $english = '<p style="text-align: left; direction: ltr;">LTR in RTL document.</p>';
        SharedHtml::addHtml($section, $english, false, false);
        SharedHtml::addHtml($section, $english, false, false);
        SharedHtml::addHtml($section, $html, false, false);
        SharedHtml::addHtml($section, $html, false, false);
        $writer = new HTML($doc);
        $content = $writer->getContent();
        self::assertSame(3, substr_count($content, '<span style="direction: rtl;">'));
        self::assertSame(2, substr_count($content, '<span style="direction: ltr;">'));
        self::assertSame(3, substr_count($content, '<p style="direction: rtl;">'));
        self::assertSame(2, substr_count($content, '<p style="text-align: left;">'));
    }
}
