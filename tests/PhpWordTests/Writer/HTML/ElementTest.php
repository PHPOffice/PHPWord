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

use DateTime;
use DOMDocument;
use DOMXPath;
use PhpOffice\PhpWord\Element\Text as TextElement;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\HTML\Element\Text;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace.
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unmatched elements.
     */
    public function testUnmatchedElements(): void
    {
        $elements = ['Container', 'Footnote', 'Image', 'Link', 'ListItem', 'ListItemRun', 'Table', 'Title', 'Bookmark'];
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element\\' . $element;
            $parentWriter = new HTML();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($parentWriter, $newElement);

            self::assertEquals('', $object->write());
        }
    }

    /**
     * Test write element text.
     */
    public function testWriteTextElement(): void
    {
        $object = new Text(new HTML(), new TextElement(htmlspecialchars('A', ENT_COMPAT, 'UTF-8')));
        $object->setOpeningText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setClosingText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setWithoutP(true);

        self::assertEquals(htmlspecialchars('-A-', ENT_COMPAT, 'UTF-8'), $object->write());
    }

    /**
     * Test write TrackChange.
     */
    public function testWriteTrackChanges(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('my dummy text');
        $text->setChangeInfo(TrackChange::INSERTED, 'author name');
        $text2 = $section->addText('my other text');
        $text2->setTrackChange(new TrackChange(TrackChange::DELETED, 'another author', new DateTime()));

        $dom = $this->getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals(1, $xpath->query('/html/body/p[1]/ins')->length);
        self::assertEquals(1, $xpath->query('/html/body/p[2]/del')->length);
    }

    /**
     * Tests writing table with col span.
     */
    public function testWriteColSpan(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $row1 = $table->addRow();
        $cell11 = $row1->addCell(1000, ['gridSpan' => 2, 'bgColor' => '6086B8']);
        $cell11->addText('cell spanning 2 bellow');
        $row2 = $table->addRow();
        $cell21 = $row2->addCell(500, ['bgColor' => 'ffffff']);
        $cell21->addText('first cell');
        $cell22 = $row2->addCell(500);
        $cell22->addText('second cell');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals(1, $xpath->query('/html/body/table/tr[1]/td')->length);
        self::assertEquals('2', $xpath->query('/html/body/table/tr/td[1]')->item(0)->attributes->getNamedItem('colspan')->textContent);
        self::assertEquals(2, $xpath->query('/html/body/table/tr[2]/td')->length);

        self::assertEquals('#6086B8', $xpath->query('/html/body/table/tr[1]/td')->item(0)->attributes->getNamedItem('bgcolor')->textContent);
        self::assertEquals('#ffffff', $xpath->query('/html/body/table/tr[1]/td')->item(0)->attributes->getNamedItem('color')->textContent);
        self::assertEquals('#ffffff', $xpath->query('/html/body/table/tr[2]/td')->item(0)->attributes->getNamedItem('bgcolor')->textContent);
        self::assertNull($xpath->query('/html/body/table/tr[2]/td')->item(0)->attributes->getNamedItem('color'));
    }

    /**
     * Tests writing table with row span.
     */
    public function testWriteRowSpan(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();

        $row1 = $table->addRow();
        $row1->addCell(1000, ['vMerge' => 'restart'])->addText('row spanning 3 bellow');
        $row1->addCell(500)->addText('first cell being spanned');

        $row2 = $table->addRow();
        $row2->addCell(null, ['vMerge' => 'continue']);
        $row2->addCell(500)->addText('second cell being spanned');

        $row3 = $table->addRow();
        $row3->addCell(null, ['vMerge' => 'continue']);
        $row3->addCell(500)->addText('third cell being spanned');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals(2, $xpath->query('/html/body/table/tr[1]/td')->length);
        self::assertEquals('3', $xpath->query('/html/body/table/tr[1]/td[1]')->item(0)->attributes->getNamedItem('rowspan')->textContent);
        self::assertEquals(1, $xpath->query('/html/body/table/tr[2]/td')->length);
    }

    private function getAsHTML(PhpWord $phpWord)
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    public function testWriteTitleTextRun(): void
    {
        $expected = 'Title with TextRun';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $textRun = new TextRun();
        $textRun->addText($expected);

        $section->addTitle($textRun);

        $htmlWriter = new HTML($phpWord);
        $content = $htmlWriter->getContent();

        self::assertStringContainsString($expected, $content);
    }

    /**
     * Test write element ListItemRun.
     */
    public function testListItemRun(): void
    {
        $expected1 = 'List item run 1';
        $expected2 = 'List item run 1 in bold';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $listItemRun = $section->addListItemRun(0, null, 'MyParagraphStyle');
        $listItemRun->addText($expected1);
        $listItemRun->addText($expected2, ['bold' => true]);

        $htmlWriter = new HTML($phpWord);
        $content = $htmlWriter->getContent();

        $dom = new DOMDocument();
        $dom->loadHTML($content);

        self::assertEquals($expected1, $dom->getElementsByTagName('p')->item(0)->textContent);
        self::assertEquals($expected2, $dom->getElementsByTagName('p')->item(1)->textContent);
    }

    /**
     * Tests writing table with layout.
     */
    public function testWriteTableLayout(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTable();

        $table1 = $section->addTable(['layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED]);
        $row1 = $table1->addRow();
        $row1->addCell()->addText('fixed layout table');

        $table2 = $section->addTable(['layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_AUTO]);
        $row2 = $table2->addRow();
        $row2->addCell()->addText('auto layout table');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals('table-layout: fixed;', $xpath->query('/html/body/table[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('table-layout: auto;', $xpath->query('/html/body/table[2]')->item(0)->attributes->getNamedItem('style')->textContent);
    }
}
