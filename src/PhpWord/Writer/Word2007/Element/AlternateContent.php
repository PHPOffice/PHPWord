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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\TrackChange;

/**
 * TextRun element writer.
 *
 * @since 0.10.0
 */
class AlternateContent extends AbstractElement
{
    /**
     * Write text element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\AlternateContent) {
            return;
        }


        $xmlWriter->startElement('w:r'); //w:r
        $xmlWriter->startElement('mc:AlternateContent'); //mc:AlternateContent
        $xmlWriter->startElement('mc:Fallback'); //mc:Fallback
        $xmlWriter->startElement('w:pict'); //w:pict

        $this->writeShape();

        $xmlWriter->endElement(); //w:pict
        $xmlWriter->endElement(); // mc:Fallback
        $xmlWriter->endElement(); // mc:AlternateContent
        $xmlWriter->endElement(); // w:r
    }

    protected function writeShape()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();

        $shape = $element->getShape();
        $line = $element->getLine();

        if ($line) {
            $xmlWriter->startElement('v:line');
            $shape = $line;
        } else {
            $xmlWriter->startElement('v:shape');
        }

        $xmlWriter->writeAttribute('id', $shape->getId());
        $xmlWriter->writeAttributeIf($shape->getSpid() != null, 'o:spid', $shape->getSpid());
        $xmlWriter->writeAttributeIf($shape->getSpt() != null, 'o:spt', $shape->getSpt());
        $xmlWriter->writeAttributeIf($shape->getType() != null, 'type', $shape->getType());
        $xmlWriter->writeAttributeIf($shape->getStyle() != null, 'style', $shape->getStyle());
        $xmlWriter->writeAttributeIf($shape->getFilled() != null, 'filled', $shape->getFilled());
        $xmlWriter->writeAttributeIf($shape->getStroked() != null, 'stroked', $shape->getStroked());
        $xmlWriter->writeAttributeIf($shape->getCoordsize() != null, 'coordsize', $shape->getCoordsize());
        $xmlWriter->writeAttributeIf($shape->getGfxdata() != null, 'o:gfxdata', $shape->getGfxdata());

        $fill = $element->getFill();
        if ($fill !== null) {
            foreach ($fill as $f => $value) if (!$value) unset($fill[$f]);
            $xmlWriter->writeElementBlock('v:fill', $fill);
        }

        $stroke = $element->getStroke();
        if ($stroke !== null) {
            foreach ($stroke as $f => $value) if (!$value) unset($stroke[$f]);
            $xmlWriter->writeElementBlock('v:stroke', $stroke);
        }

        $imagedata = $element->getImagedata();
        $xmlWriter->writeElementIf($imagedata !== null, 'w:imagedata', 'o:title', $imagedata['title']);

        $lock = $element->getLock();
        if ($lock !== null) {
            $xmlWriter->writeElementBlock('o:lock', [
                'v:ext' => $lock['ext'],
                'aspectratio' => $lock['aspectratio'],
            ]);
        }

        $textbox = $element->getTextBox();
        if ($textbox !== null) {

            $xmlWriter->startElement('v:textbox');
            $xmlWriter->writeAttribute('inset', $textbox['inset']);
            $xmlWriter->writeAttribute('style', $textbox['style']);

            $xmlWriter->startElement('w:txbxContent');
            $containerWriter = new Container($xmlWriter, $element);
            $containerWriter->write();

            $xmlWriter->endElement(); //w:txbxContent
            $xmlWriter->endElement(); //v:textbox
        }

        $xmlWriter->endElement(); //v:shape|line
    }
}
