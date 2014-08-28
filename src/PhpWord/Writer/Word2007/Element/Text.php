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

use PhpOffice\PhpWord\Element\PageBreak as PageBreakElement;
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * Text element writer
 *
 * @since 0.10.0
 */
class Text extends AbstractElement
{
    /**
     * Write text element
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Text) {
            return;
        }

        $this->writeOpeningWP();

        $this->writeOpeningChanged();

        $xmlWriter->startElement('w:r');

        $this->writeFontStyle();

        $textElement = 'w:t';
        //'w:delText' in case of deleted text
        $changed = $element->getChanged();
        if ($changed instanceof \PhpOffice\PhpWord\Element\ChangedElement) {
            if ($changed->getChangeType() == \PhpOffice\PhpWord\Element\ChangedElement::TYPE_DELETED) {
                $textElement = 'w:delText';
            }
        }
        $xmlWriter->startElement($textElement);

        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw($this->getText($element->getText()));
        $xmlWriter->endElement();
        $xmlWriter->endElement(); // w:r

        $this->writeClosingChanged();

        $this->writeClosingWP();
    }

    /**
     * Write opening
     *
     * @uses \PhpOffice\PhpWord\Writer\Word2007\Element\PageBreak::write()
     */
    protected function writeOpeningWP()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            // Paragraph style
            if (method_exists($element, 'getParagraphStyle')) {
                $this->writeParagraphStyle();
            }
            // PageBreak
            if ($this->hasPageBreakBefore()) {
                $elementWriter = new PageBreak($xmlWriter, new PageBreakElement());
                $elementWriter->write();
            }
        }
    }

    /**
     * Write ending
     */
    protected function writeClosingWP()
    {
        $xmlWriter = $this->getXmlWriter();

        if (!$this->withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write ending
     */
    protected function writeParagraphStyle()
    {
        $xmlWriter = $this->getXmlWriter();

        /** @var \PhpOffice\PhpWord\Element\Text $element Type hint */
        $element = $this->getElement();
        $paragraphStyle = $element->getParagraphStyle();
        $styleWriter = new ParagraphStyleWriter($xmlWriter, $paragraphStyle);
        $styleWriter->setIsInline(true);
        $styleWriter->write();
    }

    /**
     * Write ending
     */
    protected function writeFontStyle()
    {
        $xmlWriter = $this->getXmlWriter();

        /** @var \PhpOffice\PhpWord\Element\Text $element Type hint */
        $element = $this->getElement();
        $fontStyle = $element->getFontStyle();
        $styleWriter = new FontStyleWriter($xmlWriter, $fontStyle);
        $styleWriter->setIsInline(true);
        $styleWriter->write();
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
            $date = $changed->getDate();
            $date = date("Y-m-d\TH:i:s\Z", $date);
            $xmlWriter->writeAttribute('w:date', $date);
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
            $xmlWriter->endElement(); //w:ins | w:del
        }
    }
}
