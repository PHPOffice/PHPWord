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

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Numbering as NumberingStyle;
use PhpOffice\PhpWord\Style\NumberingLevel;

/**
 * Word2007 numbering part writer: word/numbering.xml
 */
class Numbering extends AbstractPart
{
    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $styles = Style::getStyles();
        $drawingSchema = 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing';

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:numbering');
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', $drawingSchema);
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');

        // Abstract numbering definitions
        foreach ($styles as $style) {
            if ($style instanceof NumberingStyle) {
                $levels = $style->getLevels();

                $xmlWriter->startElement('w:abstractNum');
                $xmlWriter->writeAttribute('w:abstractNumId', $style->getNumId());

                $xmlWriter->startElement('w:nsid');
                $xmlWriter->writeAttribute('w:val', $this->getRandomHexNumber());
                $xmlWriter->endElement(); // w:nsid

                $xmlWriter->startElement('w:multiLevelType');
                $xmlWriter->writeAttribute('w:val', $style->getType());
                $xmlWriter->endElement(); // w:multiLevelType

                if (is_array($levels)) {
                    foreach ($levels as $level) {
                        $this->writeLevel($xmlWriter, $level);
                    }
                }
                $xmlWriter->endElement(); // w:abstractNum
            }
        }

        // Numbering definition instances
        foreach ($styles as $style) {
            if ($style instanceof NumberingStyle) {
                $xmlWriter->startElement('w:num');
                $xmlWriter->writeAttribute('w:numId', $style->getNumId());
                $xmlWriter->startElement('w:abstractNumId');
                $xmlWriter->writeAttribute('w:val', $style->getNumId());
                $xmlWriter->endElement(); // w:abstractNumId
                $xmlWriter->endElement(); // w:num
            }
        }

        $xmlWriter->endElement(); // w:numbering

        return $xmlWriter->getData();
    }

    /**
     * Write level
     */
    private function writeLevel(XMLWriter $xmlWriter, NumberingLevel $level)
    {
        $tabPos = $level->getTabPos();
        $left = $level->getLeft();
        $hanging = $level->getHanging();
        $font = $level->getFont();
        $hint = $level->getHint();

        $xmlWriter->startElement('w:lvl');
        $xmlWriter->writeAttribute('w:ilvl', $level->getLevel());

        // Numbering level properties
        $properties = array(
            'start'   => 'start',
            'format'  => 'numFmt',
            'restart' => 'lvlRestart',
            'suffix'  => 'suff',
            'text'    => 'lvlText',
            'align'   => 'lvlJc'
        );
        foreach ($properties as $property => $nodeName) {
            $getMethod = "get{$property}";
            if (!is_null($level->$getMethod())) {
                $xmlWriter->startElement("w:{$nodeName}");
                $xmlWriter->writeAttribute('w:val', $level->$getMethod());
                $xmlWriter->endElement(); // w:start
            }
        }

        // Paragraph styles
        if (!is_null($tabPos) || !is_null($left) || !is_null($hanging)) {
            $xmlWriter->startElement('w:pPr');
            if (!is_null($tabPos)) {
                $xmlWriter->startElement('w:tabs');
                $xmlWriter->startElement('w:tab');
                $xmlWriter->writeAttribute('w:val', 'num');
                $xmlWriter->writeAttribute('w:pos', $tabPos);
                $xmlWriter->endElement(); // w:tab
                $xmlWriter->endElement(); // w:tabs
            }
            if (!is_null($left) || !is_null($hanging)) {
                $xmlWriter->startElement('w:ind');
                if (!is_null($left)) {
                    $xmlWriter->writeAttribute('w:left', $left);
                }
                if (!is_null($hanging)) {
                    $xmlWriter->writeAttribute('w:hanging', $hanging);
                }
                $xmlWriter->endElement(); // w:ind
            }
            $xmlWriter->endElement(); // w:pPr
        }

        // Font styles
        if (!is_null($font) || !is_null($hint)) {
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:rFonts');
            if (!is_null($font)) {
                $xmlWriter->writeAttribute('w:ascii', $font);
                $xmlWriter->writeAttribute('w:hAnsi', $font);
                $xmlWriter->writeAttribute('w:cs', $font);
            }
            if (!is_null($hint)) {
                $xmlWriter->writeAttribute('w:hint', $hint);
            }
            $xmlWriter->endElement(); // w:rFonts
            $xmlWriter->endElement(); // w:rPr
        }
        $xmlWriter->endElement(); // w:lvl
    }

    /**
     * Get random hexadecimal number value
     *
     * @param int $length
     * @return string
     */
    private function getRandomHexNumber($length = 8)
    {
        return strtoupper(substr(md5(rand()), 0, $length));
    }
}
