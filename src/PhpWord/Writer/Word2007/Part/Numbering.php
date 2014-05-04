<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Numbering as NumberingStyle;
use PhpOffice\PhpWord\Style\NumberingLevel;

/**
 * Word2007 numbering part writer
 */
class Numbering extends AbstractPart
{
    /**
     * Write word/numbering.xml
     */
    public function writeNumbering()
    {
        $styles = Style::getStyles();

        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:numbering');
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
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
                    foreach ($levels as $levelNum => $levelObject) {
                        if ($levelObject instanceof NumberingLevel) {
                            $start = $levelObject->getStart();
                            $format = $levelObject->getFormat();
                            $restart = $levelObject->getRestart();
                            $suffix = $levelObject->getSuffix();
                            $text = $levelObject->getText();
                            $align = $levelObject->getAlign();
                            $tabPos = $levelObject->getTabPos();
                            $left = $levelObject->getLeft();
                            $hanging = $levelObject->getHanging();
                            $font = $levelObject->getFont();
                            $hint = $levelObject->getHint();

                            $xmlWriter->startElement('w:lvl');
                            $xmlWriter->writeAttribute('w:ilvl', $levelNum);

                            if (!is_null($start)) {
                                $xmlWriter->startElement('w:start');
                                $xmlWriter->writeAttribute('w:val', $start);
                                $xmlWriter->endElement(); // w:start
                            }
                            if (!is_null($format)) {
                                $xmlWriter->startElement('w:numFmt');
                                $xmlWriter->writeAttribute('w:val', $format);
                                $xmlWriter->endElement(); // w:numFmt
                            }
                            if (!is_null($restart)) {
                                $xmlWriter->startElement('w:lvlRestart');
                                $xmlWriter->writeAttribute('w:val', $restart);
                                $xmlWriter->endElement(); // w:lvlRestart
                            }
                            if (!is_null($suffix)) {
                                $xmlWriter->startElement('w:suff');
                                $xmlWriter->writeAttribute('w:val', $suffix);
                                $xmlWriter->endElement(); // w:suff
                            }
                            if (!is_null($text)) {
                                $xmlWriter->startElement('w:lvlText');
                                $xmlWriter->writeAttribute('w:val', $text);
                                $xmlWriter->endElement(); // w:start
                            }
                            if (!is_null($align)) {
                                $xmlWriter->startElement('w:lvlJc');
                                $xmlWriter->writeAttribute('w:val', $align);
                                $xmlWriter->endElement(); // w:lvlJc
                            }
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
