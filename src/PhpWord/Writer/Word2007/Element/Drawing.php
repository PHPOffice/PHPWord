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

use PhpOffice\PhpWord\Element\Drawing as DrawingElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Drawing as DrawingStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Drawing as DrawingStyleWriter;

/**
 * Drawing element writer.
 *
 * @since 0.12.0
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class Drawing extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof DrawingElement) {
            return;
        }

        $styleObj = $element->getStyle();
        $style = $styleObj->getStyleValues();
        $xmlWriter->startElement('w:r'); //w:r
        $this->writeFontStyle();
        $xmlWriter->startElement('w:drawing'); //w:drawing
        if (isset($style['inline'])) {
            if (!$this->withoutP) {
                $xmlWriter->startElement('w:p');
            }


            $xmlWriter->startElement('wp:inline');
            $xmlWriter->writeAttribute('distT', $style['inline']['distT']);
            $xmlWriter->writeAttribute('distB', $style['inline']['distB']);
            $xmlWriter->writeAttribute('distL', $style['inline']['distL']);
            $xmlWriter->writeAttribute('distR', $style['inline']['distR']);

            if (isset($style['extent'])) {
                $xmlWriter->startElement('wp:extent');
                $xmlWriter->writeAttribute('cx', $style['extent']['cx']);
                $xmlWriter->writeAttribute('cy', $style['extent']['cy']);
                $xmlWriter->endElement();
            }

            if (isset($style['effectExtent'])) {
                $xmlWriter->startElement('wp:effectExtent');
                $xmlWriter->writeAttribute('l', $style['effectExtent']['l']);
                $xmlWriter->writeAttribute('t', $style['effectExtent']['t']);
                $xmlWriter->writeAttribute('r', $style['effectExtent']['r']);
                $xmlWriter->writeAttribute('b', $style['effectExtent']['b']);
                $xmlWriter->endElement();
            }

            if (isset($style['docPr'])) {
                $xmlWriter->startElement('wp:docPr');
                $xmlWriter->writeAttribute('id', $style['docPr']['id']);
                $xmlWriter->writeAttribute('name', $style['docPr']['name']);
                $xmlWriter->writeAttribute('descr', $style['docPr']['descr']);
                $xmlWriter->endElement();
            }

            if (isset($style['nvGraphicFPr'])) {
                $xmlWriter->startElement('wp:cNvGraphicFramePr');
                $xmlWriter->startElement('a:graphicFrameLocks');
                $xmlWriter->writeAttribute('xmlns:a', $style['nvGraphicFPr']['xmlns:a']);
                $xmlWriter->writeAttribute('noChangeAspect', $style['nvGraphicFPr']['noChangeAspect']);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }

            $this->writeGraphic($style);

            $xmlWriter->endElement();

            if (!$this->withoutP) {
                $this->endElementP(); // w:p
            }
        }
        $xmlWriter->endElement(); //w:drawing
        $xmlWriter->endElement(); //w:r
    }

    public function writeGraphic($style)
    {
        if (isset($style['graphic'])) {
            $xmlWriter = $this->getXmlWriter();
            $xmlWriter->startElement('a:graphic');
            $xmlWriter->writeAttribute('xmlns:a', $style['graphic']['val']);
            $xmlWriter->startElement('a:graphicData');
            $xmlWriter->writeAttribute('uri', $style['graphic']['graphicUri']);
            $xmlWriter->startElement('pic:pic'); //pic:pic
            $xmlWriter->writeAttribute('xmlns:pic', $style['graphic']['pic']);

            if (isset($style['graphic']['nvPicPr'])) {
                $xmlWriter->startElement('pic:nvPicPr'); //pic:nvPicPr
                $xmlWriter->startElement('pic:cNvPr'); //pic:cNvPr
                $xmlWriter->writeAttribute('id', $style['graphic']['nvPicPr']['id']);
                $xmlWriter->writeAttribute('name', $style['graphic']['nvPicPr']['name']);
                $xmlWriter->writeAttribute('descr', $style['graphic']['nvPicPr']['descr']);
                if (isset($style['graphic']['nvPicPr']['cNvPicPr'])) {
                    $xmlWriter->startElement('pic:cNvPicPr'); //pic:cNvPicPr
                    $xmlWriter->startElement('a:picLocks'); //a:picLocks
                    $xmlWriter->writeAttribute('noChangeAspect', $style['graphic']['nvPicPr']['cNvPicPr']);
                    $xmlWriter->endElement(); //a:picLocks
                    $xmlWriter->endElement(); //pic:cNvPicPr
                }
                $xmlWriter->endElement(); //pic:cNvPr
                $xmlWriter->endElement(); //pic:nvPicPr
            }

            if (isset($style['graphic']['blipFill'])) {
                $xmlWriter->startElement('pic:blipFill'); //pic:blipFill
                $xmlWriter->startElement('a:blip'); //a:blip
                $xmlWriter->writeAttribute('r:embed', $style['graphic']['blipFill']['blip']);
                $xmlWriter->endElement(); //a:blip
                if (isset($style['graphic']['blipFill']['fillRect'])) {
                    $xmlWriter->startElement('a:stretch'); //a:stretch
                    $xmlWriter->writeElement('a:fillRect'); //a:fillRect
                    $xmlWriter->endElement(); //a:stretch
                }
                $xmlWriter->endElement(); //pic:blipFill
            }

            if (isset($style['graphic']['spPr'])) {
                $xmlWriter->startElement('pic:spPr'); //pic:spPr
                $xmlWriter->startElement('a:xfrm'); //a:xfrm
                $xmlWriter->startElement('a:off'); //a:off
                $xmlWriter->writeAttribute('x', $style['graphic']['spPr']['xfrm_off_x']);
                $xmlWriter->writeAttribute('y', $style['graphic']['spPr']['xfrm_off_y']);
                $xmlWriter->endElement(); //a:off
                $xmlWriter->startElement('a:ext'); //a:ext
                $xmlWriter->writeAttribute('cx', $style['graphic']['spPr']['xfrm_ext_cx']);
                $xmlWriter->writeAttribute('cy', $style['graphic']['spPr']['xfrm_ext_cy']);
                $xmlWriter->endElement(); //a:ext
                $xmlWriter->endElement(); //a:xfrm

                if (isset($style['graphic']['spPr']['prstGeom_prst'])) {
                    $xmlWriter->startElement('a:prstGeom'); //a:prstGeom
                    $xmlWriter->writeElement('a:avLst');
                    $xmlWriter->endElement(); //a:prstGeom
                }
                $xmlWriter->endElement(); //pic:spPr
            }

            $xmlWriter->endElement(); //pic:pic
            $xmlWriter->endElement();//pic:graphicData
            $xmlWriter->endElement();//pic:graphic
        }
    }
}
