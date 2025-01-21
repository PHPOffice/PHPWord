<?php

namespace PhpOffice\PhpWord\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\TrackChange;

/**
 * Text element writer for EPub3.
 */
class Text extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Text) {
            return;
        }

        $fontStyle = $element->getFontStyle();
        $paragraphStyle = $element->getParagraphStyle();

        if (!$this->withoutP) {
            $xmlWriter->startElement('p');
            if (!empty($paragraphStyle)) {
                $xmlWriter->writeAttribute('class', $paragraphStyle);
            }
        }

        if (!empty($fontStyle)) {
            $xmlWriter->startElement('span');
            if (is_string($fontStyle)) {
                $xmlWriter->writeAttribute('class', $fontStyle);
            }
        }

        $this->writeTrackChanges($element->getTrackChange(), true);
        $xmlWriter->text($element->getText());
        $this->writeTrackChanges($element->getTrackChange(), false);

        if (!empty($fontStyle)) {
            $xmlWriter->endElement(); // span
        }

        if (!$this->withoutP) {
            $xmlWriter->endElement(); // p
        }
    }

    /**
     * Write track changes.
     */
    private function writeTrackChanges(?TrackChange $trackChange, bool $isStart): void
    {
        if ($trackChange === null) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        if ($trackChange->getChangeType() === TrackChange::INSERTED) {
            $xmlWriter->startElement('ins');
            $xmlWriter->writeAttribute('class', 'phpword-change');
            $xmlWriter->writeAttribute('data-change-id', $trackChange->getElementId());
            if (!$isStart) {
                $xmlWriter->endElement();
            }
        } elseif ($trackChange->getChangeType() === TrackChange::DELETED) {
            $xmlWriter->startElement('del');
            $xmlWriter->writeAttribute('class', 'phpword-change');
            $xmlWriter->writeAttribute('data-change-id', $trackChange->getElementId());
            if (!$isStart) {
                $xmlWriter->endElement();
            }
        }
    }
}
