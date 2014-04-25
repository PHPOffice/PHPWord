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
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\Element as ElementWriter;

/**
 * Word2007 base part writer
 *
 * Write common parts of document.xml, headerx.xml, and footerx.xml
 */
class Base extends AbstractWriterPart
{
    /**
     * Write paragraph style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Paragraph $style
     * @param bool $withoutPPR
     */
    public function writeParagraphStyle(XMLWriter $xmlWriter, Paragraph $style, $withoutPPR = false)
    {

        $align = $style->getAlign();
        $spacing = $style->getSpacing();
        $spaceBefore = $style->getSpaceBefore();
        $spaceAfter = $style->getSpaceAfter();
        $indent = $style->getIndent();
        $hanging = $style->getHanging();
        $tabs = $style->getTabs();
        $widowControl = $style->getWidowControl();
        $keepNext = $style->getKeepNext();
        $keepLines = $style->getKeepLines();
        $pageBreakBefore = $style->getPageBreakBefore();

        if (!is_null($align) || !is_null($spacing) || !is_null($spaceBefore) ||
                !is_null($spaceAfter) || !is_null($indent) || !is_null($hanging) ||
                !is_null($tabs) || !is_null($widowControl) || !is_null($keepNext) ||
                !is_null($keepLines) || !is_null($pageBreakBefore)) {
            if (!$withoutPPR) {
                $xmlWriter->startElement('w:pPr');
            }

            // Alignment
            if (!is_null($align)) {
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $align);
                $xmlWriter->endElement();
            }

            // Indentation
            if (!is_null($indent) || !is_null($hanging)) {
                $xmlWriter->startElement('w:ind');
                $xmlWriter->writeAttribute('w:firstLine', 0);
                if (!is_null($indent)) {
                    $xmlWriter->writeAttribute('w:left', $indent);
                }
                if (!is_null($hanging)) {
                    $xmlWriter->writeAttribute('w:hanging', $hanging);
                }
                $xmlWriter->endElement();
            }

            // Spacing
            if (!is_null($spaceBefore) || !is_null($spaceAfter) ||
                    !is_null($spacing)) {
                $xmlWriter->startElement('w:spacing');
                if (!is_null($spaceBefore)) {
                    $xmlWriter->writeAttribute('w:before', $spaceBefore);
                }
                if (!is_null($spaceAfter)) {
                    $xmlWriter->writeAttribute('w:after', $spaceAfter);
                }
                if (!is_null($spacing)) {
                    $xmlWriter->writeAttribute('w:line', $spacing);
                    $xmlWriter->writeAttribute('w:lineRule', 'auto');
                }
                $xmlWriter->endElement();
            }

            // Pagination
            if (!$widowControl) {
                $xmlWriter->startElement('w:widowControl');
                $xmlWriter->writeAttribute('w:val', '0');
                $xmlWriter->endElement();
            }
            if ($keepNext) {
                $xmlWriter->startElement('w:keepNext');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            if ($keepLines) {
                $xmlWriter->startElement('w:keepLines');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            if ($pageBreakBefore) {
                $xmlWriter->startElement('w:pageBreakBefore');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }

            // Tabs
            if (!empty($tabs)) {
                $xmlWriter->startElement("w:tabs");
                foreach ($tabs as $tab) {
                    $xmlWriter->startElement("w:tab");
                    $xmlWriter->writeAttribute("w:val", $tab->getStopType());
                    if (!is_null($tab->getLeader())) {
                        $xmlWriter->writeAttribute("w:leader", $tab->getLeader());
                    }
                    $xmlWriter->writeAttribute("w:pos", $tab->getPosition());
                    $xmlWriter->endElement();
                }
                $xmlWriter->endElement();
            }

            if (!$withoutPPR) {
                $xmlWriter->endElement(); // w:pPr
            }
        }
    }

    /**
     * Write font style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Font $style
     */
    public function writeFontStyle(XMLWriter $xmlWriter, Font $style)
    {
        $font = $style->getName();
        $bold = $style->getBold();
        $italic = $style->getItalic();
        $color = $style->getColor();
        $size = $style->getSize();
        $fgColor = $style->getFgColor();
        $bgColor = $style->getBgColor();
        $strikethrough = $style->getStrikethrough();
        $underline = $style->getUnderline();
        $superscript = $style->getSuperScript();
        $subscript = $style->getSubScript();
        $hint = $style->getHint();

        $xmlWriter->startElement('w:rPr');

        // Font
        if ($font != PhpWord::DEFAULT_FONT_NAME) {
            $xmlWriter->startElement('w:rFonts');
            $xmlWriter->writeAttribute('w:ascii', $font);
            $xmlWriter->writeAttribute('w:hAnsi', $font);
            $xmlWriter->writeAttribute('w:eastAsia', $font);
            $xmlWriter->writeAttribute('w:cs', $font);
            //Font Content Type
            if ($hint != PhpWord::DEFAULT_FONT_CONTENT_TYPE) {
                $xmlWriter->writeAttribute('w:hint', $hint);
            }
            $xmlWriter->endElement();
        }


        // Color
        if ($color != PhpWord::DEFAULT_FONT_COLOR) {
            $xmlWriter->startElement('w:color');
            $xmlWriter->writeAttribute('w:val', $color);
            $xmlWriter->endElement();
        }

        // Size
        if ($size != PhpWord::DEFAULT_FONT_SIZE) {
            $xmlWriter->startElement('w:sz');
            $xmlWriter->writeAttribute('w:val', $size * 2);
            $xmlWriter->endElement();
            $xmlWriter->startElement('w:szCs');
            $xmlWriter->writeAttribute('w:val', $size * 2);
            $xmlWriter->endElement();
        }

        // Bold
        if ($bold) {
            $xmlWriter->writeElement('w:b', null);
        }

        // Italic
        if ($italic) {
            $xmlWriter->writeElement('w:i', null);
            $xmlWriter->writeElement('w:iCs', null);
        }

        // Underline
        if (!is_null($underline) && $underline != 'none') {
            $xmlWriter->startElement('w:u');
            $xmlWriter->writeAttribute('w:val', $underline);
            $xmlWriter->endElement();
        }

        // Strikethrough
        if ($strikethrough) {
            $xmlWriter->writeElement('w:strike', null);
        }

        // Foreground-Color
        if (!is_null($fgColor)) {
            $xmlWriter->startElement('w:highlight');
            $xmlWriter->writeAttribute('w:val', $fgColor);
            $xmlWriter->endElement();
        }

        // Background-Color
        if (!is_null($bgColor)) {
            $xmlWriter->startElement('w:shd');
            $xmlWriter->writeAttribute('w:val', "clear");
            $xmlWriter->writeAttribute('w:color', "auto");
            $xmlWriter->writeAttribute('w:fill', $bgColor);
            $xmlWriter->endElement();
        }

        // Superscript/subscript
        if ($superscript || $subscript) {
            $xmlWriter->startElement('w:vertAlign');
            $xmlWriter->writeAttribute('w:val', $superscript ? 'superscript' : 'subscript');
            $xmlWriter->endElement();
        }

        $xmlWriter->endElement();
    }

    /**
     * Write table style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @param boolean $isFullStyle
     */
    public function writeTableStyle(XMLWriter $xmlWriter, TableStyle $style, $isFullStyle = true)
    {
        $bgColor = $style->getBgColor();
        $brdCol = $style->getBorderColor();
        $brdSz = $style->getBorderSize();
        $cellMargin = $style->getCellMargin();

        // If any of the borders/margins is set, process them
        $hasBorders = false;
        for ($i = 0; $i < 6; $i++) {
            if (!is_null($brdSz[$i])) {
                $hasBorders = true;
                break;
            }
        }
        $hasMargins = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($cellMargin[$i])) {
                $hasMargins = true;
                break;
            }
        }
        if ($hasMargins || $hasBorders) {
            $xmlWriter->startElement('w:tblPr');
            if ($hasMargins) {
                $xmlWriter->startElement('w:tblCellMar');
                $this->writeMarginBorder($xmlWriter, $cellMargin);
                $xmlWriter->endElement(); // w:tblCellMar
            }
            if ($hasBorders) {
                $xmlWriter->startElement('w:tblBorders');
                $this->writeMarginBorder($xmlWriter, $brdSz, $brdCol);
                $xmlWriter->endElement(); // w:tblBorders
            }
            $xmlWriter->endElement(); // w:tblPr
        }
        // Only write background color and first row for full style
        if ($isFullStyle) {
            // Background color
            if (!is_null($bgColor)) {
                $xmlWriter->startElement('w:tcPr');
                $xmlWriter->startElement('w:shd');
                $xmlWriter->writeAttribute('w:val', 'clear');
                $xmlWriter->writeAttribute('w:color', 'auto');
                $xmlWriter->writeAttribute('w:fill', $bgColor);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
            // First Row
            $firstRow = $style->getFirstRow();
            if (!is_null($firstRow)) {
                $this->writeRowStyle($xmlWriter, 'firstRow', $firstRow);
            }
        }
    }

    /**
     * Write row style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    public function writeRowStyle(XMLWriter $xmlWriter, $type, TableStyle $style)
    {
        $bgColor = $style->getBgColor();

        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', $type);
        $xmlWriter->startElement('w:tcPr');
        if (!is_null($bgColor)) {
            $xmlWriter->startElement('w:shd');
            $xmlWriter->writeAttribute('w:val', 'clear');
            $xmlWriter->writeAttribute('w:color', 'auto');
            $xmlWriter->writeAttribute('w:fill', $bgColor);
            $xmlWriter->endElement(); // w:shd
        }

        // Borders
        $brdSz = $style->getBorderSize();
        $brdCol = $style->getBorderColor();
        $hasBorders = false;
        for ($i = 0; $i < 6; $i++) {
            if (!is_null($brdSz[$i])) {
                $hasBorders = true;
                break;
            }
        }
        if ($hasBorders) {
            $xmlWriter->startElement('w:tcBorders');
            $this->writeMarginBorder($xmlWriter, $brdSz, $brdCol);
            $xmlWriter->endElement(); // w:tcBorders
        }

        $xmlWriter->endElement(); // w:tcPr
        $xmlWriter->endElement(); // w:tblStylePr
    }

    /**
     * Write footnote reference element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Cell $style
     */
    public function writeCellStyle(XMLWriter $xmlWriter, Cell $style)
    {
        $bgColor = $style->getBgColor();
        $valign = $style->getVAlign();
        $textDir = $style->getTextDirection();
        $brdSz = $style->getBorderSize();
        $brdCol = $style->getBorderColor();
        $hasBorders = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($brdSz[$i])) {
                $hasBorders = true;
                break;
            }
        }

        $styles = (!is_null($bgColor) || !is_null($valign) || !is_null($textDir) || $hasBorders) ? true : false;

        if ($styles) {
            if (!is_null($textDir)) {
                $xmlWriter->startElement('w:textDirection');
                $xmlWriter->writeAttribute('w:val', $textDir);
                $xmlWriter->endElement();
            }

            if (!is_null($bgColor)) {
                $xmlWriter->startElement('w:shd');
                $xmlWriter->writeAttribute('w:val', 'clear');
                $xmlWriter->writeAttribute('w:color', 'auto');
                $xmlWriter->writeAttribute('w:fill', $bgColor);
                $xmlWriter->endElement();
            }

            if (!is_null($valign)) {
                $xmlWriter->startElement('w:vAlign');
                $xmlWriter->writeAttribute('w:val', $valign);
                $xmlWriter->endElement();
            }

            if ($hasBorders) {
                $defaultColor = $style->getDefaultBorderColor();

                $xmlWriter->startElement('w:tcBorders');
                $this->writeMarginBorder($xmlWriter, $brdSz, $brdCol, array('defaultColor' => $defaultColor));
                $xmlWriter->endElement();
            }
        }
        $gridSpan = $style->getGridSpan();
        if (!is_null($gridSpan)) {
            $xmlWriter->startElement('w:gridSpan');
            $xmlWriter->writeAttribute('w:val', $gridSpan);
            $xmlWriter->endElement();
        }

        $vMerge = $style->getVMerge();
        if (!is_null($vMerge)) {
            $xmlWriter->startElement('w:vMerge');
            $xmlWriter->writeAttribute('w:val', $vMerge);
            $xmlWriter->endElement();
        }
    }

    /**
     * Write inline paragraph style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Paragraph|string $styleParagraph
     * @param boolean $withoutPPR
     */
    public function writeInlineParagraphStyle(XMLWriter $xmlWriter, $styleParagraph = null, $withoutPPR = false)
    {
        if ($styleParagraph instanceof Paragraph) {
            $this->writeParagraphStyle($xmlWriter, $styleParagraph, $withoutPPR);
        } else {
            if (!is_null($styleParagraph)) {
                if (!$withoutPPR) {
                    $xmlWriter->startElement('w:pPr');
                }
                $xmlWriter->startElement('w:pStyle');
                $xmlWriter->writeAttribute('w:val', $styleParagraph);
                $xmlWriter->endElement();
                if (!$withoutPPR) {
                    $xmlWriter->endElement();
                }
            }
        }
    }

    /**
     * Write inline font style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Font|string $styleFont
     */
    public function writeInlineFontStyle(XMLWriter $xmlWriter, $styleFont = null)
    {
        if ($styleFont instanceof Font) {
            $this->writeFontStyle($xmlWriter, $styleFont);
        } else {
            if (!is_null($styleFont)) {
                $xmlWriter->startElement('w:rPr');
                $xmlWriter->startElement('w:rStyle');
                $xmlWriter->writeAttribute('w:val', $styleFont);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }
    }

    /**
     * Write container elements
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\AbstractElement $container
     */
    public function writeContainerElements(XMLWriter $xmlWriter, AbstractElement $container)
    {
        // Check allowed elements
        $elmCommon = array('Text', 'Link', 'TextBreak', 'Image', 'Object');
        $elmMainCell = array_merge($elmCommon, array('TextRun', 'ListItem', 'CheckBox'));
        $allowedElements = array(
            'Section'  => array_merge($elmMainCell, array('Table', 'Footnote', 'Title', 'PageBreak', 'TOC')),
            'Header'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Footer'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Cell'     => array_merge($elmMainCell, array('PreserveText', 'Footnote', 'Endnote')),
            'TextRun'  => array_merge($elmCommon, array('Footnote', 'Endnote')),
            'Footnote' => $elmCommon,
            'Endnote'  => $elmCommon,
        );
        $containerName = get_class($container);
        $containerName = substr($containerName, strrpos($containerName, '\\') + 1);
        if (!array_key_exists($containerName, $allowedElements)) {
            throw new Exception('Invalid container.');
        }

        // Loop through elements
        $elements = $container->getElements();
        $withoutP = in_array($containerName, array('TextRun', 'Footnote', 'Endnote')) ? true : false;
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof AbstractElement) {
                    $elementWriter = new ElementWriter($xmlWriter, $this, $element, $withoutP);
                    $elementWriter->write();
                }
            }
        } else {
            if ($containerName == 'Cell') {
                $elementWriter = new ElementWriter($xmlWriter, $this, new TextBreak(), $withoutP);
                $elementWriter->write();
            }
        }
    }

    /**
     * Write margin or border
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param array $sizes
     * @param array $colors
     * @param array $attributes
     */
    protected function writeMarginBorder(XMLWriter $xmlWriter, $sizes, $colors = array(), $attributes = array())
    {
        $sides = array('top', 'left', 'right', 'bottom', 'insideH', 'insideV');
        $sizeCount = count($sizes) - 1;
        for ($i = 0; $i < $sizeCount; $i++) {
            if (!is_null($sizes[$i])) {
                $xmlWriter->startElement('w:' . $sides[$i]);
                if (!empty($colors)) {
                    if (is_null($colors[$i]) && !empty($attributes)) {
                        if (array_key_exists('defaultColor', $attributes)) {
                            $colors[$i] = $attributes['defaultColor'];
                        }
                    }
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $sizes[$i]);
                    $xmlWriter->writeAttribute('w:color', $colors[$i]);
                    if (!empty($attributes)) {
                        if (array_key_exists('space', $attributes)) {
                            $xmlWriter->writeAttribute('w:space', '24');
                        }
                    }
                } else {
                    $xmlWriter->writeAttribute('w:w', $sizes[$i]);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                }
                $xmlWriter->endElement();
            }
        }
    }
}
