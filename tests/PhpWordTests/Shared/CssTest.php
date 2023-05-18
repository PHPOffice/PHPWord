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

namespace PhpOffice\PhpWordTests\Shared;

use PhpOffice\PhpWord\Shared\Css;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Shared\Css.
 */
class CssTest extends TestCase
{
    public function testEmptyCss(): void
    {
        $css = new Css('');
        $css->process();

        self::assertEquals([], $css->getStyles());
    }

    public function testBasicCss(): void
    {
        $cssContent = '.pStyle {
          font-size:15px;
        }';

        $css = new Css($cssContent);
        $css->process();

        self::assertEquals([
            '.pStyle' => [
                'font-size' => '15px',
            ],
        ], $css->getStyles());
        self::assertEquals([
            'font-size' => '15px',
        ], $css->getStyle('.pStyle'));
    }
}
