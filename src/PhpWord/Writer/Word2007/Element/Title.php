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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Shared\String;

/**
 * TextRun element writer
 *
 * @since 0.10.0
 */
class Title extends Element
{
    /**
     * Write title element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Title) {
            return;
        }

        $bookmarkId = $this->element->getBookmarkId();
        $anchor = '_Toc' . ($bookmarkId + 252634154);
        $style = $this->element->getStyle();
        $text = htmlspecialchars($this->element->getText());
        $text = String::controlCharacterPHP2OOXML($text);

        $this->xmlWriter->startElement('w:p');

        if (!empty($style)) {
            $this->xmlWriter->startElement('w:pPr');
            $this->xmlWriter->startElement('w:pStyle');
            $this->xmlWriter->writeAttribute('w:val', $style);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:bookmarkStart');
        $this->xmlWriter->writeAttribute('w:id', $bookmarkId);
        $this->xmlWriter->writeAttribute('w:name', $anchor);
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeRaw($text);
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:bookmarkEnd');
        $this->xmlWriter->writeAttribute('w:id', $bookmarkId);
        $this->xmlWriter->endElement();

        $this->xmlWriter->endElement();
    }
}
