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

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * ODText styloes part writer: styles.xml
 */
class Styles extends AbstractPart
{
    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('office:document-styles');
        $this->writeCommonRootAttributes($xmlWriter);

        // Font declarations
        $this->writeFontFaces($xmlWriter);

        // Office styles
        $xmlWriter->startElement('office:styles');
        $this->writePart($xmlWriter, 'Default');
        $this->writePart($xmlWriter, 'Named');
        $xmlWriter->endElement();

        // Automatic styles
        $xmlWriter->startElement('office:automatic-styles');
        $this->writePart($xmlWriter, 'PageLayout');
        $this->writePart($xmlWriter, 'Master');
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // office:document-styles

        return $xmlWriter->getData();
    }

    /**
     * Write style part
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $subStyle
     */
    private function writePart(XMLWriter $xmlWriter, $subStyle)
    {
        $writerClass = "PhpOffice\\PhpWord\\Writer\\ODText\\Style\\{$subStyle}Style";
        $styleWriter = new $writerClass($xmlWriter);
        $styleWriter->write();
    }
}
