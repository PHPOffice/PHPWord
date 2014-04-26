<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

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

        $align = $this->style->getAlign();
        $spacing = $this->style->getSpacing();
        $spaceBefore = $this->style->getSpaceBefore();
        $spaceAfter = $this->style->getSpaceAfter();
        $indent = $this->style->getIndent();
        $hanging = $this->style->getHanging();
        $tabs = $this->style->getTabs();
        $widowControl = $this->style->getWidowControl();
        $keepNext = $this->style->getKeepNext();
        $keepLines = $this->style->getKeepLines();
        $pageBreakBefore = $this->style->getPageBreakBefore();

        if (!is_null($align) || !is_null($spacing) || !is_null($spaceBefore) ||
                !is_null($spaceAfter) || !is_null($indent) || !is_null($hanging) ||
                !is_null($tabs) || !is_null($widowControl) || !is_null($keepNext) ||
                !is_null($keepLines) || !is_null($pageBreakBefore)) {
            if (!$this->withoutPPR) {
                $this->xmlWriter->startElement('w:pPr');
            }

            // Alignment
            if (!is_null($align)) {
                $this->xmlWriter->startElement('w:jc');
                $this->xmlWriter->writeAttribute('w:val', $align);
                $this->xmlWriter->endElement();
            }

            // Indentation
            if (!is_null($indent) || !is_null($hanging)) {
                $this->xmlWriter->startElement('w:ind');
                $this->xmlWriter->writeAttribute('w:firstLine', 0);
                if (!is_null($indent)) {
                    $this->xmlWriter->writeAttribute('w:left', $indent);
                }
                if (!is_null($hanging)) {
                    $this->xmlWriter->writeAttribute('w:hanging', $hanging);
                }
                $this->xmlWriter->endElement();
            }

            // Spacing
            if (!is_null($spaceBefore) || !is_null($spaceAfter) ||
                    !is_null($spacing)) {
                $this->xmlWriter->startElement('w:spacing');
                if (!is_null($spaceBefore)) {
                    $this->xmlWriter->writeAttribute('w:before', $spaceBefore);
                }
                if (!is_null($spaceAfter)) {
                    $this->xmlWriter->writeAttribute('w:after', $spaceAfter);
                }
                if (!is_null($spacing)) {
                    $this->xmlWriter->writeAttribute('w:line', $spacing);
                    $this->xmlWriter->writeAttribute('w:lineRule', 'auto');
                }
                $this->xmlWriter->endElement();
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
            if (!empty($tabs)) {
                $this->xmlWriter->startElement("w:tabs");
                foreach ($tabs as $tab) {
                    $this->xmlWriter->startElement("w:tab");
                    $this->xmlWriter->writeAttribute("w:val", $tab->getStopType());
                    if (!is_null($tab->getLeader())) {
                        $this->xmlWriter->writeAttribute("w:leader", $tab->getLeader());
                    }
                    $this->xmlWriter->writeAttribute("w:pos", $tab->getPosition());
                    $this->xmlWriter->endElement();
                }
                $this->xmlWriter->endElement();
            }

            if (!$this->withoutPPR) {
                $this->xmlWriter->endElement(); // w:pPr
            }
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
