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

namespace PhpOffice\PhpWordTests\Writer\RTF;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Writer\RTF;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class RichTextTitleTest extends TestCase
{
    /**
     * Test empty styles.
     */
    public function testRichTextTitleFromHtml(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $htmlContent = '<h1>This is heading 1</h1><h2>This is heading 2</h2>';
        Html::addHtml($section, $htmlContent, false, false);
        $elements = $section->getElements();
        self::assertInstanceOf(Title::class, $elements[0]);
        self::assertInstanceOf(TextRun::class, $elements[0]->getText());

        $writer = new RTF($phpWord);
        $contents = $writer->getContent();
        self::assertStringContainsString('{This is heading 1}\par', $contents);
        self::assertStringContainsString('{This is heading 2}\par', $contents);
    }
}
