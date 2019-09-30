<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Row as RowStyle;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Style subnamespace
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles
     */
    public function testTable()
    {
        $xmlWriter = new XMLWriter();
        $table = new Table($xmlWriter, new TableStyle(array(
            'ColumnWidths' => array(
                new Auto(),
                Absolute::from('in', 1),
                new Percent(15),
            ),
        )));
        $table->write();

        $this->assertEquals("<style:style style:name=\"\" style:family=\"table\">\n  <style:table-properties style:rel-width=\"100\" table:align=\"center\"/>\n</style:style>\n<style:style style:name=\".0\" style:family=\"table-column\">\n  <style:table-column-properties/>\n</style:style>\n<style:style style:name=\".1\" style:family=\"table-column\">\n  <style:table-column-properties style:column-width=\"2.54cm\"/>\n</style:style>\n<style:style style:name=\".2\" style:family=\"table-column\">\n  <style:table-column-properties style:column-width=\"15.00%\"/>\n</style:style>\n", $xmlWriter->getData());
    }

    /**
     * Test wrong type of column width
     * @expectedException \Exception
     * @expectedExceptionMessage Column widths must be specified with `PhpOffice\PhpWord\Style\Lengths\Length`
     */
    public function testWrongColumnWidthType()
    {
        new Table(new XMLWriter(), new TableStyle(array(
            'ColumnWidths' => array(
                '5pt',
            ),
        )));
    }

    /**
     * Test wrong type of column width
     * @expectedException \Exception
     * @expectedExceptionMessage Unsupported width `class@anonymous
     */
    public function testWrongColumnWidthClass()
    {
        $table = new Table(new XMLWriter(), new TableStyle(array(
            'ColumnWidths' => array(
                new class() extends Length {
                    public function isSpecified(): bool
                    {
                        return true;
                    }
                },
            ),
        )));
        $table->write();
    }

    /**
     * Test wrong type of style
     * @expectedException \Exception
     * @expectedExceptionMessage Incorrect value provided for style. PhpOffice\PhpWord\Style\Table expected, PhpOffice\PhpWord\Style\Row provided
     */
    public function testWrongStyle()
    {
        $xmlWriter = new XMLWriter();
        $table = new Table($xmlWriter, new RowStyle());
        $table->write();

        $this->assertEquals('', $xmlWriter->getData());
    }
}
