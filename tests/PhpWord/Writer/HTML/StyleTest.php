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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style subnamespace
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles
     */
    public function testEmptyStyles()
    {
        $styles = array('Font', 'Paragraph', 'Image');
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Style\\' . $style;
            $object = new $objectClass();

            $this->assertEquals('', $object->write());
        }
    }

    private function getAsHTML(PhpWord $phpWord)
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    /**
     * Tests writing table with border styles
     */
    public function testWriteTableBorders()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $bsnone = array('borderStyle' => 'none');
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

        $bstyle = array('borderStyle' => 'dashed', 'borderColor' => 'red');
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $bstyle = array(
            'borderTopStyle'    => 'dotted',
            'borderLeftStyle'   => 'dashed',
            'borderRightStyle'  => 'dashed',
            'borderBottomStyle' => 'dotted',
            'borderTopColor'    => 'blue',
            'borderLeftColor'   => 'green',
            'borderRightColor'  => 'green',
            'borderBottomColor' => 'blue',
            );
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $bstyle = array('borderStyle' => 'solid', 'borderSize' => 5);
        $table1 = $section->addTable($bstyle);
        $row1 = $table1->addRow();
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 1');
        $row1->addCell(null, $bstyle)->addText('Row 1 Cell 2');
        $row2 = $table1->addRow();
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 1');
        $row2->addCell(null, $bstyle)->addText('Row 2 Cell 2');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $cssnone = ' border-top-style: none;'
            . ' border-left-style: none; '
            . 'border-bottom-style: none; '
            . 'border-right-style: none;';
        $this->assertEquals("table-layout: auto;$cssnone", $xpath->query('/html/body/div/table[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[1]/tr[1]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[1]/tr[1]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[1]/tr[2]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[1]/tr[2]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);

        $this->assertEmpty($xpath->query('/html/body/div/table[2]')->item(0)->attributes->getNamedItem('style'));
        $this->assertEmpty($xpath->query('/html/body/div/table[2]/tr[1]/td[1]')->item(0)->attributes->getNamedItem('style'));
        $this->assertEmpty($xpath->query('/html/body/div/table[2]/tr[1]/td[2]')->item(0)->attributes->getNamedItem('style'));
        $this->assertEmpty($xpath->query('/html/body/div/table[2]/tr[2]/td[1]')->item(0)->attributes->getNamedItem('style'));
        $this->assertEmpty($xpath->query('/html/body/div/table[2]/tr[2]/td[2]')->item(0)->attributes->getNamedItem('style'));

        $cssnone = ' border-top-style: dashed;'
            . ' border-top-color: red;'
            . ' border-left-style: dashed;'
            . ' border-left-color: red;'
            . ' border-bottom-style: dashed;'
            . ' border-bottom-color: red;'
            . ' border-right-style: dashed;'
            . ' border-right-color: red;';
        $this->assertEquals("table-layout: auto;$cssnone", $xpath->query('/html/body/div/table[3]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[3]/tr[1]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[3]/tr[1]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[3]/tr[2]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[3]/tr[2]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);

        $cssnone = ' border-top-style: dotted;'
            . ' border-top-color: blue;'
            . ' border-left-style: dashed;'
            . ' border-left-color: green;'
            . ' border-bottom-style: dotted;'
            . ' border-bottom-color: blue;'
            . ' border-right-style: dashed;'
            . ' border-right-color: green;';
        $this->assertEquals("table-layout: auto;$cssnone", $xpath->query('/html/body/div/table[4]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[4]/tr[1]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[4]/tr[1]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[4]/tr[2]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[4]/tr[2]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);

        $cssnone = ' border-top-style: solid;'
            . ' border-top-width: 0.25pt;'
            . ' border-left-style: solid;'
            . ' border-left-width: 0.25pt;'
            . ' border-bottom-style: solid;'
            . ' border-bottom-width: 0.25pt;'
            . ' border-right-style: solid;'
            . ' border-right-width: 0.25pt;';
        $this->assertEquals("table-layout: auto;$cssnone", $xpath->query('/html/body/div/table[5]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[5]/tr[1]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[5]/tr[1]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[5]/tr[2]/td[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        $this->assertEquals($cssnone, $xpath->query('/html/body/div/table[5]/tr[2]/td[2]')->item(0)->attributes->getNamedItem('style')->textContent);
    }
}
