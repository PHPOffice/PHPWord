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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML;

use PhpOffice\PhpWord\Element\Text as TextElement;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\HTML\Element\Text;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unmatched elements
     */
    public function testUnmatchedElements()
    {
        $elements = array('Container', 'Footnote', 'Image', 'Link', 'ListItem', 'ListItemRun', 'Table', 'Title', 'Bookmark');
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Element\\' . $element;
            $parentWriter = new HTML();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($parentWriter, $newElement);

            $this->assertEquals('', $object->write());
        }
    }

    /**
     * Test write element text
     */
    public function testWriteTextElement()
    {
        $object = new Text(new HTML(), new TextElement(htmlspecialchars('A', ENT_COMPAT, 'UTF-8')));
        $object->setOpeningText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setClosingText(htmlspecialchars('-', ENT_COMPAT, 'UTF-8'));
        $object->setWithoutP(true);

        $this->assertEquals(htmlspecialchars('-A-', ENT_COMPAT, 'UTF-8'), $object->write());
    }

    /**
     * Test write TrackChange
     */
    public function testWriteTrackChanges()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('my dummy text');
        $text->setChangeInfo(TrackChange::INSERTED, 'author name');
        $text2 = $section->addText('my other text');
        $text2->setTrackChange(new TrackChange(TrackChange::DELETED, 'another author', new \DateTime()));

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $this->assertEquals(1, $xpath->query('/html/body/p[1]/ins')->length);
        $this->assertEquals(1, $xpath->query('/html/body/p[2]/del')->length);
    }

    /**
     * Tests writing table with col span
     */
    public function testWriteColSpan()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $row1 = $table->addRow();
        $cell11 = $row1->addCell(1000, array('gridSpan' => 2, 'bgColor' => '6086B8'));
        $cell11->addText('cell spanning 2 bellow');
        $row2 = $table->addRow();
        $cell21 = $row2->addCell(500, array('bgColor' => 'ffffff'));
        $cell21->addText('first cell');
        $cell22 = $row2->addCell(500);
        $cell22->addText('second cell');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $this->assertEquals(1, $xpath->query('/html/body/table/tr[1]/td')->length);
        $this->assertEquals('2', $xpath->query('/html/body/table/tr/td[1]')->item(0)->attributes->getNamedItem('colspan')->textContent);
        $this->assertEquals(2, $xpath->query('/html/body/table/tr[2]/td')->length);

        $this->assertEquals('#6086B8', $xpath->query('/html/body/table/tr[1]/td')->item(0)->attributes->getNamedItem('bgcolor')->textContent);
        $this->assertEquals('#ffffff', $xpath->query('/html/body/table/tr[1]/td')->item(0)->attributes->getNamedItem('color')->textContent);
        $this->assertEquals('#ffffff', $xpath->query('/html/body/table/tr[2]/td')->item(0)->attributes->getNamedItem('bgcolor')->textContent);
        $this->assertNull($xpath->query('/html/body/table/tr[2]/td')->item(0)->attributes->getNamedItem('color'));
    }

    /**
     * Tests writing table with row span
     */
    public function testWriteRowSpan()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();

        $row1 = $table->addRow();
        $row1->addCell(1000, array('vMerge' => 'restart'))->addText('row spanning 3 bellow');
        $row1->addCell(500)->addText('first cell being spanned');

        $row2 = $table->addRow();
        $row2->addCell(null, array('vMerge' => 'continue'));
        $row2->addCell(500)->addText('second cell being spanned');

        $row3 = $table->addRow();
        $row3->addCell(null, array('vMerge' => 'continue'));
        $row3->addCell(500)->addText('third cell being spanned');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $this->assertEquals(2, $xpath->query('/html/body/table/tr[1]/td')->length);
        $this->assertEquals('3', $xpath->query('/html/body/table/tr[1]/td[1]')->item(0)->attributes->getNamedItem('rowspan')->textContent);
        $this->assertEquals(1, $xpath->query('/html/body/table/tr[2]/td')->length);
    }

    private function getAsHTML(PhpWord $phpWord)
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    public function testWriteTitleTextRun()
    {
        $expected = 'Title with TextRun';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $textRun = new TextRun();
        $textRun->addText($expected);

        $section->addTitle($textRun);

        $htmlWriter = new HTML($phpWord);
        $content = $htmlWriter->getContent();

        $this->assertContains($expected, $content);
    }

    /**
     * Test write element ListItemRun
     */
    public function testListItemRun()
    {
        $expected1 = 'List item run 1';
        $expected2 = 'List item run 1 in bold';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $listItemRun = $section->addListItemRun(0, null, 'MyParagraphStyle');
        $listItemRun->addText($expected1);
        $listItemRun->addText($expected2, array('bold' => true));

        $htmlWriter = new HTML($phpWord);
        $content = $htmlWriter->getContent();

        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        $this->assertEquals($expected1, $dom->getElementsByTagName('p')->item(0)->textContent);
        $this->assertEquals($expected2, $dom->getElementsByTagName('p')->item(1)->textContent);
    }

    /**
     * Tests writing table with layout
     */
    public function testWriteTableLayout()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTable();

        $table1 = $section->addTable(array('layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED));
        $row1 = $table1->addRow();
        $row1->addCell()->addText('fixed layout table');

        $table2 = $section->addTable(array('layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_AUTO));
        $row2 = $table2->addRow();
        $row2->addCell()->addText('auto layout table');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $this->assertEquals('table-layout: fixed;', $xpath->query('/html/body/table[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals('table-layout: auto;', $xpath->query('/html/body/table[2]')->item(0)->attributes->getNamedItem('style')->textContent);
    }
}
