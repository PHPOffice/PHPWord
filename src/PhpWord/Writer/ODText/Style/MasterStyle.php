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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

/**
 * Master style writer
 *
 * @since 0.11.0
 */
class MasterStyle extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('office:master-styles');

        $xmlWriter->startElement('style:master-page');
        $xmlWriter->writeAttribute('style:name', 'Standard');
        $xmlWriter->writeAttribute('style:page-layout-name', 'Mpm1');
        $xmlWriter->endElement(); // style:master-page

        $xmlWriter->endElement(); // office:master-styles
    }
}
