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

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html as SharedHtml;
use PhpOffice\PhpWord\Writer\HTML as HtmlWriter;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class HtmlHeadingsTest extends TestCase
{
    public function testRoundTripHeadings(): void
    {
        Settings::setOutputEscapingEnabled(true);
        $originalDoc = new PhpWord();
        $originalDoc->addTitleStyle(1, ['size' => 20]);
        $section = $originalDoc->addSection();
        $expectedStrings = [];
        $section->addTitle('Title 1', 1);
        $expectedStrings[] = '<h1 style="font-size: 20pt;">Title 1</h1>';
        for ($i = 2; $i <= 6; ++$i) {
            $textRun = new TextRun();
            $textRun->addText('Title ');
            $textRun->addText("$i", ['italic' => true]);
            $section->addTitle($textRun, $i);
            $expectedStrings[] = "<h$i>Title <span style=\"font-style: italic;\">$i</span></h$i>";
        }
        $writer = new HtmlWriter($originalDoc);
        $content = $writer->getContent();
        foreach ($expectedStrings as $expectedString) {
            self::assertStringContainsString($expectedString, $content);
        }

        $newDoc = new PhpWord();
        $newSection = $newDoc->addSection();
        SharedHtml::addHtml($newSection, $content, true);
        $newWriter = new HtmlWriter($newDoc);
        $newContent = $newWriter->getContent();
        // Reader transforms Text to TextRun,
        //  but result is functionally the same.
        $firstStringAsTextRun = '<h1><span style="font-size: 20pt;">Title 1</span></h1>';
        self::assertSame($content, str_replace($firstStringAsTextRun, $expectedStrings[0], $newContent));
    }
}
