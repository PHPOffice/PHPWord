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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * Text element writer
 *
 * @since 0.10.0
 */
class Text extends AbstractElement
{
    /**
     * Write text element.
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Text) {
            return;
        }

        $this->startElementP();

        $changed = $element->getChanged();
        if ($changed) {
            $this->writeOpeningChanged();
        }

        $xmlWriter->startElement('w:r');

        $this->writeFontStyle();

        $textElement = 'w:t';
        //'w:delText' in case of deleted text
        if (($changed) && ($changed->getChangeType() == \PhpOffice\PhpWord\Element\ChangedElement::TYPE_DELETED)) {
            $textElement = 'w:delText';
        }
        $xmlWriter->startElement($textElement);

        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->writeText($this->getText($element->getText()));
        $xmlWriter->endElement();
        $xmlWriter->endElement(); // w:r

        $this->writeClosingChanged();

        $this->endElementP(); // w:p
    }

    /**
     * Write opening of changed element
     */
    protected function writeOpeningChanged()
    {
        $element = $this->getElement();
        $changed = $element->getChanged();

        $xmlWriter = $this->getXmlWriter();

        if ($changed instanceof \PhpOffice\PhpWord\Element\ChangedElement) {
            if (($changed->getChangeType() == \PhpOffice\PhpWord\Element\ChangedElement::TYPE_INSERTED)) {
                $xmlWriter->startElement('w:ins');
            } elseif ($changed->getChangeType() == \PhpOffice\PhpWord\Element\ChangedElement::TYPE_DELETED) {
                $xmlWriter->startElement('w:del');
            }
            $xmlWriter->writeAttribute('w:author', $changed->getAuthor());
            $xmlWriter->writeAttribute('w:date', $changed->getDate()->format('Y-m-d\TH:i:s\Z'));
            $xmlWriter->writeAttribute('w:id', $element->getElementId());
        }
    }

    /**
     * Write ending
     */
    protected function writeClosingChanged()
    {
        $element = $this->getElement();
        $changed = $element->getChanged();

        $xmlWriter = $this->getXmlWriter();

        if ($changed instanceof \PhpOffice\PhpWord\Element\ChangedElement) {
            $xmlWriter->endElement(); // w:ins|w:del
        }
    }
}
