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
use PhpOffice\PhpWord\Shared\Html as SharedHtml;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace.
 */
class ImgAltTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unmatched elements.
     */
    public function testDirection(): void
    {
        $src = 'tests/PhpWordTests/_files/images/firefox.png';
        $doc = new PhpWord();
        $section = $doc->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/><img src="' . $src . '" style="float: left"; alt="Firefox logo"/></p>';
        SharedHtml::addHtml($section, $html, false, false);
        $writer = new HTML($doc);
        $content = $writer->getContent();
        self::assertStringContainsString('alt="Firefox logo"', $content);
    }
}
