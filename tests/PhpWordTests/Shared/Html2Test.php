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

use Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWordTests\AbstractWebServerEmbedded;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class Html2Test extends AbstractWebServerEmbedded
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('loadHTML');
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '');
    }

    public function testCssOnIdElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<html>'
            . '<head>'
            . '<title>Id Test</title>'
            . '<style>#bold1 {font-weight: bold; margin: 10px;}</style>'
            . '</head><body>'
            . '<p id="bold1">test1.</p>'
            . '</body></html>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $marginPath = '/w:document/w:body/w:p/w:pPr/w:spacing';
        self::assertSame('150', $doc->getElement($marginPath)->getAttribute('w:before'));
        self::assertSame('150', $doc->getElement($marginPath)->getAttribute('w:after'));
        $path = '/w:document/w:body/w:p/w:r';
        self::assertSame('test1.', $doc->getElement($path)->nodeValue);
        $boldPath = $path . '/w:rPr/w:b';
        self::assertSame('1', $doc->getElement($boldPath)->getAttribute('w:val'));
    }

    public function testListTypes(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<ol type="1"><li>Decimal number first</li><li>second</li></ol>'
            . '<ol type="a"><li>Lowercase first</li><li>second</li></ol>'
            . '<ol type="A"><li>Uppercase first</li><li>second</li></ol>'
            . '<ol type="i"><li>Lower roman first</li><li>second</li></ol>'
            . '<ol type="I"><li>Upper roman first</li><li>second</li></ol>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $item = 1;
        $expected = '1';
        $path = "/w:document/w:body/w:p[$item]";
        self::assertSame('Decimal number first', $doc->getElement("$path/w:r")->nodeValue);
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
        ++$item;
        $path = "/w:document/w:body/w:p[$item]";
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));

        ++$item;
        $expected = '2';
        $path = "/w:document/w:body/w:p[$item]";
        self::assertSame('Lowercase first', $doc->getElement("$path/w:r")->nodeValue);
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
        ++$item;
        $path = "/w:document/w:body/w:p[$item]";
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));

        ++$item;
        $expected = '3';
        $path = "/w:document/w:body/w:p[$item]";
        self::assertSame('Uppercase first', $doc->getElement("$path/w:r")->nodeValue);
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
        ++$item;
        $path = "/w:document/w:body/w:p[$item]";
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));

        ++$item;
        $expected = '4';
        $path = "/w:document/w:body/w:p[$item]";
        self::assertSame('Lower roman first', $doc->getElement("$path/w:r")->nodeValue);
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
        ++$item;
        $path = "/w:document/w:body/w:p[$item]";
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));

        ++$item;
        $expected = '5';
        $path = "/w:document/w:body/w:p[$item]";
        self::assertSame('Upper roman first', $doc->getElement("$path/w:r")->nodeValue);
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
        ++$item;
        $path = "/w:document/w:body/w:p[$item]";
        $numIdPath = $path . '/w:pPr/w:numPr/w:numId';
        self::assertSame($expected, $doc->getElement($numIdPath)->getAttribute('w:val'));
    }

    public function testPadding(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table><tbody>'
            . '<tr>'
            . '<td style="padding: 20px">20</td>'
            . '<td style="padding: 20px 30px">20 30</td>'
            . '</tr><tr>'
            . '<td style="padding: 20px 30px 40px">20 30 40</td>'
            . '<td style="padding: 20px 30px 40px 50px">20 30 40 50</td>'
            . '</tr></tbody></table>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $item = 1;
        $td = 1;
        $path = "/w:document/w:body/w:tbl/w:tr[$item]/w:tc[$td]";
        self::assertSame('20', $doc->getElement("$path/w:p/w:r")->nodeValue);
        $tcMarPath = $path . '/w:tcPr/w:tcMar';
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:top')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:start')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:bottom')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:end')->getAttribute('w:w'));

        ++$td;
        $path = "/w:document/w:body/w:tbl/w:tr[$item]/w:tc[$td]";
        self::assertSame('20 30', $doc->getElement("$path/w:p/w:r")->nodeValue);
        $tcMarPath = $path . '/w:tcPr/w:tcMar';
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:top')->getAttribute('w:w'));
        self::assertSame('450', $doc->getElement($tcMarPath . '/w:start')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:bottom')->getAttribute('w:w'));
        self::assertSame('450', $doc->getElement($tcMarPath . '/w:end')->getAttribute('w:w'));

        $item = 1;
        $td = 1;
        $path = "/w:document/w:body/w:tbl/w:tr[$item]/w:tc[$td]";
        self::assertSame('20', $doc->getElement("$path/w:p/w:r")->nodeValue);
        $tcMarPath = $path . '/w:tcPr/w:tcMar';
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:top')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:start')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:bottom')->getAttribute('w:w'));
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:end')->getAttribute('w:w'));

        ++$item;
        $td = 1;
        $path = "/w:document/w:body/w:tbl/w:tr[$item]/w:tc[$td]";
        self::assertSame('20 30 40', $doc->getElement("$path/w:p/w:r")->nodeValue);
        $tcMarPath = $path . '/w:tcPr/w:tcMar';
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:top')->getAttribute('w:w'));
        self::assertSame('450', $doc->getElement($tcMarPath . '/w:start')->getAttribute('w:w'));
        self::assertSame('600', $doc->getElement($tcMarPath . '/w:bottom')->getAttribute('w:w'));
        self::assertSame('450', $doc->getElement($tcMarPath . '/w:end')->getAttribute('w:w'));

        ++$td;
        $path = "/w:document/w:body/w:tbl/w:tr[$item]/w:tc[$td]";
        self::assertSame('20 30 40 50', $doc->getElement("$path/w:p/w:r")->nodeValue);
        $tcMarPath = $path . '/w:tcPr/w:tcMar';
        self::assertSame('300', $doc->getElement($tcMarPath . '/w:top')->getAttribute('w:w'));
        self::assertSame('750', $doc->getElement($tcMarPath . '/w:start')->getAttribute('w:w'));
        self::assertSame('600', $doc->getElement($tcMarPath . '/w:bottom')->getAttribute('w:w'));
        self::assertSame('450', $doc->getElement($tcMarPath . '/w:end')->getAttribute('w:w'));
    }
}
