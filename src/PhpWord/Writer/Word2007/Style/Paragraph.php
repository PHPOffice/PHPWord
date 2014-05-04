<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Writer\Word2007\Style\Tab;
use PhpOffice\PhpWord\Writer\Word2007\Style\Indentation;
use PhpOffice\PhpWord\Writer\Word2007\Style\Spacing;

/**
 * Paragraph style writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Without w:pPr
     *
     * @var bool
     */
    private $withoutPPR = false;

    /**
     * Is inline in element
     *
     * @var bool
     */
    private $isInline = false;

    /**
     * Write style
     */
    public function write()
    {
        $isStyleName = $this->isInline && !is_null($this->style) && is_string($this->style);
        if ($isStyleName) {
            if (!$this->withoutPPR) {
                $this->xmlWriter->startElement('w:pPr');
            }
            $this->xmlWriter->startElement('w:pStyle');
            $this->xmlWriter->writeAttribute('w:val', $this->style);
            $this->xmlWriter->endElement();
            if (!$this->withoutPPR) {
                $this->xmlWriter->endElement();
            }
        } else {
            $this->writeStyle();
        }
    }

    /**
     * Write full style
     */
    private function writeStyle()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Paragraph)) {
            return;
        }

        $widowControl = $this->style->getWidowControl();
        $keepNext = $this->style->getKeepNext();
        $keepLines = $this->style->getKeepLines();
        $pageBreakBefore = $this->style->getPageBreakBefore();

        if (!$this->withoutPPR) {
            $this->xmlWriter->startElement('w:pPr');
        }

        // Alignment
        if (!is_null($this->style->getAlign())) {
            $this->xmlWriter->startElement('w:jc');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getAlign());
            $this->xmlWriter->endElement();
        }

        // Indentation
        if (!is_null($this->style->getIndentation())) {
            $styleWriter = new Indentation($this->xmlWriter, $this->style->getIndentation());
            $styleWriter->write();
        }

        // Spacing
        if (!is_null($this->style->getSpace())) {
            $styleWriter = new Spacing($this->xmlWriter, $this->style->getSpace());
            $styleWriter->write();
        }

        // Pagination
        if (!$widowControl) {
            $this->xmlWriter->startElement('w:widowControl');
            $this->xmlWriter->writeAttribute('w:val', '0');
            $this->xmlWriter->endElement();
        }
        if ($keepNext) {
            $this->xmlWriter->startElement('w:keepNext');
            $this->xmlWriter->writeAttribute('w:val', '1');
            $this->xmlWriter->endElement();
        }
        if ($keepLines) {
            $this->xmlWriter->startElement('w:keepLines');
            $this->xmlWriter->writeAttribute('w:val', '1');
            $this->xmlWriter->endElement();
        }
        if ($pageBreakBefore) {
            $this->xmlWriter->startElement('w:pageBreakBefore');
            $this->xmlWriter->writeAttribute('w:val', '1');
            $this->xmlWriter->endElement();
        }

        // Tabs
        $tabs = $this->style->getTabs();
        if (!empty($tabs)) {
            $this->xmlWriter->startElement("w:tabs");
            foreach ($tabs as $tab) {
                $styleWriter = new Tab($this->xmlWriter, $tab);
                $styleWriter->write();
            }
            $this->xmlWriter->endElement();
        }

        if (!$this->withoutPPR) {
            $this->xmlWriter->endElement(); // w:pPr
        }
    }

    /**
     * Set without w:pPr
     *
     * @param bool $value
     */
    public function setWithoutPPR($value)
    {
        $this->withoutPPR = $value;
    }

    /**
     * Set is inline
     *
     * @param bool $value
     */
    public function setIsInline($value)
    {
        $this->isInline = $value;
    }
}
