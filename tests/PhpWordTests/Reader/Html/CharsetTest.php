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

namespace PhpOffice\PhpWordTests\Reader\Html;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Reader\HTML;
use PHPUnit\Framework\TestCase;
use Throwable;

class CharsetTest extends TestCase
{
    /**
     * @dataProvider providerCharset
     */
    public function testCharset(string $filename, string $expectedResult): void
    {
        if ($expectedResult === 'exception') {
            $this->expectException(Throwable::class);
            $this->expectExceptionMessage('unknown encoding');
        }
        $directory = 'tests/PhpWordTests/_files/html';
        $reader = new HTML();
        $doc = $reader->load("$directory/$filename");
        $sections = $doc->getSections();
        self::assertCount(1, $sections);
        $section = $sections[0];
        $elements = $section->getElements();
        $element = $elements[0];
        self::assertInstanceOf(TextRun::class, $element);
        self::assertSame($expectedResult, $element->getText());
    }

    public static function providerCharset(): array
    {
        return [
            ['charset.ISO-8859-1.html', 'À1'],
            ['charset.ISO-8859-1.html4.html', 'À1'],
            ['charset.ISO-8859-2.html', 'Ŕ1'],
            ['charset.nocharset.html', 'À1'],
            ['charset.UTF-8.html', 'À1'],
            ['charset.UTF-8.bom.html', 'À1'],
            ['charset.UTF-16.bebom.html', 'À1'],
            ['charset.UTF-16.lebom.html', 'À1'],
            ['charset.gb18030.html', '电视机'],
            'loadhtml gives its best shot' => ['charset.unknown.html', "Ã\u{80}1"],
        ];
    }
}
