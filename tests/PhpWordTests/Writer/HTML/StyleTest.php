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

use DOMDocument;
use DOMXPath;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style subnamespace.
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles.
     */
    public function testEmptyStyles(): void
    {
        $styles = ['Font', 'Paragraph', 'Image'];
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Style\\' . $style;
            $object = new $objectClass();

            self::assertEquals('', $object->write());
        }
    }

    private function getAsHTML(PhpWord $phpWord): DOMDocument
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    /**
     * Tests writing table with border styles.
     */
    public function testWriteTableBorders(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $bsnone = ['borderStyle' => 'none'];
        $table1 = $section->addTable($bsnone);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bsnone)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bsnone)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bsnone)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bsnone)->addText('Row 2 Cell 2');

        $table1 = $section->addTable();
        $row1 = $table1->addRow();
        $row1->addCell()->addText('Row 1 Cell 1');
        $row1->addCell()->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell()->addText('Row 2 Cell 1');
        $row2->addCell()->addText('Row 2 Cell 2');

        $bstyle = ['borderStyle' => 'dashed', 'borderColor' => 'red'];
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $bstyle = [
            'borderTopStyle' => 'dotted',
            'borderLeftStyle' => 'dashed',
            'borderRightStyle' => 'dashed',
            'borderBottomStyle' => 'dotted',
            'borderTopColor' => 'blue',
            'borderLeftColor' => 'green',
            'borderRightColor' => 'green',
            'borderBottomColor' => 'blue',
        ];
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $bstyle = ['borderStyle' => 'solid', 'borderSize' => 5];
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $phpWord->addTableStyle('tstyle', ['borderStyle' => 'solid', 'borderSize' => 5]);
        $table1 = $section->addTable('tstyle');
        $row1 = $table1->addRow();
        $row1->addCell(null, 'tstyle')->addText('Row 1 Cell 1');
        $row1->addCell(null, 'tstyle')->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, 'tstyle')->addText('Row 2 Cell 1');
        $row2->addCell(null, 'tstyle')->addText('Row 2 Cell 2');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        $cssnone = ' border-top-style: none;'
            . ' border-left-style: none; '
            . 'border-bottom-style: none; '
            . 'border-right-style: none;';
        self::assertEquals("table-layout: auto;$cssnone", self::getTextContent($xpath, '/html/body/div/table[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[1]/tr[1]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[1]/tr[1]/td[2]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[1]/tr[2]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[1]/tr[2]/td[2]', 0, 'style'));

        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[2]', 0, 'style'));
        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[2]/tr[1]/td[1]', 0, 'style'));
        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[2]/tr[1]/td[2]', 0, 'style'));
        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[2]/tr[2]/td[1]', 0, 'style'));
        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[2]/tr[2]/td[2]', 0, 'style'));

        $cssnone = ' border-top-style: dashed;'
            . ' border-top-color: red;'
            . ' border-left-style: dashed;'
            . ' border-left-color: red;'
            . ' border-bottom-style: dashed;'
            . ' border-bottom-color: red;'
            . ' border-right-style: dashed;'
            . ' border-right-color: red;';
        self::assertEquals("table-layout: auto;$cssnone", self::getTextContent($xpath, '/html/body/div/table[3]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[3]/tr[1]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[3]/tr[1]/td[2]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[3]/tr[2]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[3]/tr[2]/td[2]', 0, 'style'));

        $cssnone = ' border-top-style: dotted;'
            . ' border-top-color: blue;'
            . ' border-left-style: dashed;'
            . ' border-left-color: green;'
            . ' border-bottom-style: dotted;'
            . ' border-bottom-color: blue;'
            . ' border-right-style: dashed;'
            . ' border-right-color: green;';
        self::assertEquals("table-layout: auto;$cssnone", self::getTextContent($xpath, '/html/body/div/table[4]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[4]/tr[1]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[4]/tr[1]/td[2]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[4]/tr[2]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[4]/tr[2]/td[2]', 0, 'style'));

        $cssnone = ' border-top-style: solid;'
            . ' border-top-width: 0.25pt;'
            . ' border-left-style: solid;'
            . ' border-left-width: 0.25pt;'
            . ' border-bottom-style: solid;'
            . ' border-bottom-width: 0.25pt;'
            . ' border-right-style: solid;'
            . ' border-right-width: 0.25pt;';
        self::assertEquals("table-layout: auto;$cssnone", self::getTextContent($xpath, '/html/body/div/table[5]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[5]/tr[1]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[5]/tr[1]/td[2]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[5]/tr[2]/td[1]', 0, 'style'));
        self::assertEquals($cssnone, self::getTextContent($xpath, '/html/body/div/table[5]/tr[2]/td[2]', 0, 'style'));

        self::assertEmpty(self::getNamedItem($xpath, '/html/body/div/table[6]', 0, 'style'));
        self::assertEquals('tstyle', self::getTextContent($xpath, '/html/body/div/table[6]', 0, 'class'));
        $style = self::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(preg_match('/^[.]tstyle[^\\r\\n]*/m', $style, $matches));
        self::assertEquals(".tstyle {table-layout: auto;$cssnone}", $matches[0]);
    }

    private static function getTextContent(DOMXPath $xpath, string $query, int $itemNumber = 0, string $namedItem = ''): string
    {
        $returnVal = '';
        $item = $xpath->query($query);
        if ($item === false) {
            self::fail('Unexpected false return from xpath query');
        } elseif ($namedItem !== '') {
            $returnVal = $item->item($itemNumber)->attributes->getNamedItem($namedItem)->textContent;
        } else {
            $returnVal = $item->item($itemNumber)->textContent;
        }

        return $returnVal;
    }

    /** @return mixed */
    private static function getNamedItem(DOMXPath $xpath, string $query, int $itemNumber, string $namedItem)
    {
        $returnVal = '';
        $item = $xpath->query($query);
        if ($item === false) {
            self::fail('Unexpected false return from xpath query');
        } else {
            $returnVal = $item->item($itemNumber)->attributes->getNamedItem($namedItem);
        }

        return $returnVal;
    }
}
