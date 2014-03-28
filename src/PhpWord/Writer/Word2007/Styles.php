<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Word2007 styles part writer
 */
class Styles extends Base
{
    /**
     * PhpWord object
     *
     * @var PhpWord
     */
    private $phpWord;

    /**
     * Write word/styles.xml
     *
     * @param PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function writeStyles(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(
                XMLWriter::STORAGE_DISK,
                $this->getParentWriter()->getDiskCachingDirectory()
            );
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }
        $this->phpWord = $phpWord;
        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:styles');
        $xmlWriter->writeAttribute(
            'xmlns:r',
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships'
        );
        $xmlWriter->writeAttribute(
            'xmlns:w',
            'http://schemas.openxmlformats.org/wordprocessingml/2006/main'
        );
        // Write default styles
        $styles = Style::getStyles();
        $this->writeDefaultStyles($xmlWriter, $styles);
        // Write other styles
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if ($styleName == 'Normal') {
                    continue;
                }
                if ($style instanceof Font) {

                    $paragraphStyle = $style->getParagraphStyle();
                    $styleType = $style->getStyleType();

                    $type = ($styleType == 'title') ? 'paragraph' : 'character';

                    if (!is_null($paragraphStyle)) {
                        $type = 'paragraph';
                    }

                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', $type);

                    if ($styleType == 'title') {
                        $arrStyle = explode('_', $styleName);
                        $styleId = 'Heading' . $arrStyle[1];
                        $styleName = 'heading ' . $arrStyle[1];
                        $styleLink = 'Heading' . $arrStyle[1] . 'Char';
                        $xmlWriter->writeAttribute('w:styleId', $styleId);

                        $xmlWriter->startElement('w:link');
                        $xmlWriter->writeAttribute('w:val', $styleLink);
                        $xmlWriter->endElement();
                    }

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    if (!is_null($paragraphStyle)) {
                        // Point parent style to Normal
                        $xmlWriter->startElement('w:basedOn');
                        $xmlWriter->writeAttribute('w:val', 'Normal');
                        $xmlWriter->endElement();
                        $this->_writeParagraphStyle($xmlWriter, $paragraphStyle);
                    }

                    $this->_writeTextStyle($xmlWriter, $style);

                    $xmlWriter->endElement();

                } elseif ($style instanceof Paragraph) {
                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', 'paragraph');
                    $xmlWriter->writeAttribute('w:customStyle', '1');
                    $xmlWriter->writeAttribute('w:styleId', $styleName);

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    // Parent style
                    $basedOn = $style->getBasedOn();
                    if (!is_null($basedOn)) {
                        $xmlWriter->startElement('w:basedOn');
                        $xmlWriter->writeAttribute('w:val', $basedOn);
                        $xmlWriter->endElement();
                    }

                    // Next paragraph style
                    $next = $style->getNext();
                    if (!is_null($next)) {
                        $xmlWriter->startElement('w:next');
                        $xmlWriter->writeAttribute('w:val', $next);
                        $xmlWriter->endElement();
                    }

                    $this->_writeParagraphStyle($xmlWriter, $style);
                    $xmlWriter->endElement();

                } elseif ($style instanceof \PhpOffice\PhpWord\Style\Table) {
                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', 'table');
                    $xmlWriter->writeAttribute('w:customStyle', '1');
                    $xmlWriter->writeAttribute('w:styleId', $styleName);

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    $xmlWriter->startElement('w:uiPriority');
                    $xmlWriter->writeAttribute('w:val', '99');
                    $xmlWriter->endElement();

                    $this->_writeTableStyle($xmlWriter, $style);

                    $xmlWriter->endElement(); // w:style
                }
            }
        }

        $xmlWriter->endElement(); // w:styles

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write default font and other default styles
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param array $styles
     */
    private function writeDefaultStyles(XMLWriter $xmlWriter, $styles)
    {
        $fontName = $this->phpWord->getDefaultFontName();
        $fontSize = $this->phpWord->getDefaultFontSize();

        // Default font
        $xmlWriter->startElement('w:docDefaults');
        $xmlWriter->startElement('w:rPrDefault');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:rFonts');
        $xmlWriter->writeAttribute('w:ascii', $fontName);
        $xmlWriter->writeAttribute('w:hAnsi', $fontName);
        $xmlWriter->writeAttribute('w:eastAsia', $fontName);
        $xmlWriter->writeAttribute('w:cs', $fontName);
        $xmlWriter->endElement(); // w:rFonts
        $xmlWriter->startElement('w:sz');
        $xmlWriter->writeAttribute('w:val', $fontSize * 2);
        $xmlWriter->endElement(); // w:sz
        $xmlWriter->startElement('w:szCs');
        $xmlWriter->writeAttribute('w:val', $fontSize * 2);
        $xmlWriter->endElement(); // w:szCs
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->endElement(); // w:rPrDefault
        $xmlWriter->endElement(); // w:docDefaults

        // Normal style
        $xmlWriter->startElement('w:style');
        $xmlWriter->writeAttribute('w:type', 'paragraph');
        $xmlWriter->writeAttribute('w:default', '1');
        $xmlWriter->writeAttribute('w:styleId', 'Normal');
        $xmlWriter->startElement('w:name');
        $xmlWriter->writeAttribute('w:val', 'Normal');
        $xmlWriter->endElement(); // w:name
        if (array_key_exists('Normal', $styles)) {
            $this->_writeParagraphStyle($xmlWriter, $styles['Normal']);
        }
        $xmlWriter->endElement(); // w:style

        // FootnoteReference style
        if (!array_key_exists('FootnoteReference', $styles)) {
            $xmlWriter->startElement('w:style');
            $xmlWriter->writeAttribute('w:type', 'character');
            $xmlWriter->writeAttribute('w:styleId', 'FootnoteReference');
            $xmlWriter->startElement('w:name');
            $xmlWriter->writeAttribute('w:val', 'Footnote Reference');
            $xmlWriter->endElement(); // w:name
            $xmlWriter->writeElement('w:semiHidden');
            $xmlWriter->writeElement('w:unhideWhenUsed');
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:vertAlign');
            $xmlWriter->writeAttribute('w:val', 'superscript');
            $xmlWriter->endElement(); // w:vertAlign
            $xmlWriter->endElement(); // w:rPr
            $xmlWriter->endElement(); // w:style
        }
    }
}
