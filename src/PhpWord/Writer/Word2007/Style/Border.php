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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\BorderSide;

/**
 * Margin border style writer
 *
 * @since 0.10.0
 */
trait Border
{
    /**
     * Write side.
     *
     * @see http://officeopenxml.com/WPborders.php
     * @see http://www.officeopenxml.com/WPsectionBorders.php
     * @see http://officeopenxml.com/WPtableBorders.php
     * @see http://officeopenxml.com/WPtableCellMargins.php
     * @see http://officeopenxml.com/WPtableCellProperties-Borders.php
     */
    private function writeBorder(XMLWriter $xmlWriter, string $side, BorderSide $border)
    {
        if (preg_match('/([^a-zA-Z0-9])/', $side)) {
            throw new Exception(sprintf('Invalid character in side `%s`', $side));
        } elseif (($border->getSize()->toInt('eop') ?? 0) === 0) {
            // Don't write 0 width borders
            return;
        }

        $xmlWriter->startElement('w:' . $side);
        $xmlWriter->writeAttribute('w:val', $border->getStyle()->getStyle());
        $xmlWriter->writeAttribute('w:sz', max(2, min(96, $border->getSize()->toInt('eop'))));
        $xmlWriter->writeAttribute('w:space', $border->getSpace()->toInt('pt'));
        $xmlWriter->writeAttribute('w:color', $border->getColor()->toHexOrName() ?? 'auto');
        $xmlWriter->writeAttribute('w:shadow', $border->getShadow() ? 'true' : 'false');
        $xmlWriter->endElement();
    }
}
