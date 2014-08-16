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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Spacing between lines and above/below paragraph style writer
 *
 * @since 0.10.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return void
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Spacing) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('w:spacing');

        $before = $style->getBefore();
        $xmlWriter->writeAttributeIf(!is_null($before), 'w:before', $this->convertTwip($before));

        $after = $style->getAfter();
        $xmlWriter->writeAttributeIf(!is_null($after), 'w:after', $this->convertTwip($after));

        $line = $style->getLine();
        $xmlWriter->writeAttributeIf(!is_null($line), 'w:line', $line);

        $xmlWriter->writeAttributeIf(!is_null($line), 'w:lineRule', $style->getRule());

        $xmlWriter->endElement();
    }
}
