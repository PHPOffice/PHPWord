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

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class HtmlFullTest extends TestCase
{
    /**
     * Test unit conversion functions with various numbers.
     */
    public function testAddFullHtml(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $htmlContent = <<<EOF
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<title>Testing Head Section</title>
<meta name="author" content="PhpWord Test" />
<meta name="description" content="testing html read including meta tags" />
<style type='text/css'>
.boldtext {font-weight: bold;}
</style>
<script>
/* */
</script>
</head>
<body>
<p>This is <span class="boldtext">bold</span> text.</p>
</body>
</html>
EOF;
        Html::addHtml($section, $htmlContent, true, true);
        self::assertSame('Testing Head Section', $phpWord->getDocInfo()->getTitle());
        self::assertSame('PhpWord Test', $phpWord->getDocInfo()->getCreator());
        self::assertSame('testing html read including meta tags', $phpWord->getDocInfo()->getDescription());
        $elements = $section->getElements();
        self::assertCount(1, $elements);
        $element = $elements[0];
        self::assertInstanceOf(TextRun::class, $element);
        $textElements = $element->getElements();
        self::assertCount(3, $textElements);

        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        $style = $textElement->getFontStyle();
        self::assertInstanceOf(Font::class, $style);
        self::assertNotTrue($style->isBold());
        self::assertSame('This is ', $textElement->getText());

        $textElement = $textElements[1];
        self::assertInstanceOf(Text::class, $textElement);
        $style = $textElement->getFontStyle();
        self::assertInstanceOf(Font::class, $style);
        self::assertTrue($style->isBold());
        self::assertSame('bold', $textElement->getText());

        $textElement = $textElements[2];
        self::assertInstanceOf(Text::class, $textElement);
        $style = $textElement->getFontStyle();
        self::assertInstanceOf(Font::class, $style);
        self::assertNotTrue($style->isBold());
        self::assertSame(' text.', $textElement->getText());
    }
}
