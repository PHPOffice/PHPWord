<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Container\Container;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\PreserveText;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Object;
use PhpOffice\PhpWord\Element\Footnote;
use PhpOffice\PhpWord\Element\CheckBox;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Word2007 base part writer
 *
 * Write common parts of document.xml, headerx.xml, and footerx.xml
 */
class Base extends WriterPart
{
    /**
     * Write text element
     *
     * @param XMLWriter $xmlWriter
     * @param Text $text
     * @param boolean $withoutP
     */
    protected function writeText(XMLWriter $xmlWriter, Text $text, $withoutP = false)
    {
        $styleFont = $text->getFontStyle();
        $styleParagraph = $text->getParagraphStyle();
        $strText = htmlspecialchars($text->getText());
        $strText = String::controlCharacterPHP2OOXML($strText);

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
            $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
        }
        $xmlWriter->startElement('w:r');
        $this->writeInlineFontStyle($xmlWriter, $styleFont);
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw($strText);
        $xmlWriter->endElement();
        $xmlWriter->endElement(); // w:r
        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write textrun  element
     *
     * @param XMLWriter $xmlWriter
     * @param TextRun $textrun
     */
    protected function writeTextRun(XMLWriter $xmlWriter, TextRun $textrun)
    {
        $styleParagraph = $textrun->getParagraphStyle();
        $xmlWriter->startElement('w:p');
        $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
        $this->writeContainerElements($xmlWriter, $textrun);
        $xmlWriter->endElement(); // w:p
    }

    /**
     * Write link element
     *
     * @param XMLWriter $xmlWriter
     * @param Link $link
     * @param boolean $withoutP
     */
    protected function writeLink(XMLWriter $xmlWriter, Link $link, $withoutP = false)
    {
        $rID = $link->getRelationId();
        $linkName = $link->getLinkName();
        if (is_null($linkName)) {
            $linkName = $link->getLinkSrc();
        }
        $styleFont = $link->getFontStyle();
        $styleParagraph = $link->getParagraphStyle();

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
            $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
        }
        $xmlWriter->startElement('w:hyperlink');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rID);
        $xmlWriter->writeAttribute('w:history', '1');
        $xmlWriter->startElement('w:r');
        $this->writeInlineFontStyle($xmlWriter, $styleFont);
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve'); // needed because of drawing spaces before and after text
        $xmlWriter->writeRaw($linkName);
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:hyperlink
        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write title element
     *
     * @param XMLWriter $xmlWriter
     * @param Title $title
     */
    protected function writeTitle(XMLWriter $xmlWriter, Title $title)
    {
        $text = htmlspecialchars($title->getText());
        $text = String::controlCharacterPHP2OOXML($text);
        $anchor = $title->getAnchor();
        $bookmarkId = $title->getBookmarkId();
        $style = $title->getStyle();

        $xmlWriter->startElement('w:p');

        if (!empty($style)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $style);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:bookmarkStart');
        $xmlWriter->writeAttribute('w:id', $bookmarkId);
        $xmlWriter->writeAttribute('w:name', $anchor);
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeRaw($text);
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:bookmarkEnd');
        $xmlWriter->writeAttribute('w:id', $bookmarkId);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write preserve text element
     *
     * @param XMLWriter $xmlWriter
     * @param PreserveText $textrun
     */
    protected function writePreserveText(XMLWriter $xmlWriter, PreserveText $textrun)
    {
        $styleFont = $textrun->getFontStyle();
        $styleParagraph = $textrun->getParagraphStyle();

        $arrText = $textrun->getText();
        if (!is_array($arrText)) {
            $arrText = array($arrText);
        }

        $xmlWriter->startElement('w:p');
        $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
        foreach ($arrText as $text) {
            if (substr($text, 0, 1) == '{') {
                $text = substr($text, 1, -1);

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'begin');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $this->writeInlineFontStyle($xmlWriter, $styleFont);
                $xmlWriter->startElement('w:instrText');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->writeRaw($text);
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'separate');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'end');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            } else {
                $text = htmlspecialchars($text);
                $text = String::controlCharacterPHP2OOXML($text);

                $xmlWriter->startElement('w:r');
                $this->writeInlineFontStyle($xmlWriter, $styleFont);
                $xmlWriter->startElement('w:t');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->writeRaw($text);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->endElement(); // p
    }

    /**
     * Write text break element
     *
     * @param XMLWriter $xmlWriter
     * @param TextBreak $element
     */
    protected function writeTextBreak(XMLWriter $xmlWriter, TextBreak $element = null, $withoutP = false)
    {
        if (!$withoutP) {
            $hasStyle = false;
            $styleFont = null;
            $styleParagraph = null;
            if (!is_null($element)) {
                $styleFont = $element->getFontStyle();
                $styleParagraph = $element->getParagraphStyle();
                $hasStyle = !is_null($styleFont) || !is_null($styleParagraph);
            }
            if ($hasStyle) {
                $xmlWriter->startElement('w:p');
                $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
                if (!is_null($styleFont)) {
                    $xmlWriter->startElement('w:pPr');
                    $this->writeInlineFontStyle($xmlWriter, $styleFont);
                    $xmlWriter->endElement(); // w:pPr
                }
                $xmlWriter->endElement(); // w:p
            } else {
                $xmlWriter->writeElement('w:p');
            }
        } else {
            $xmlWriter->writeElement('w:br');
        }
    }

    /**
     * Write list item element
     *
     * @param XMLWriter $xmlWriter
     * @param ListItem $listItem
     */
    protected function writeListItem(XMLWriter $xmlWriter, ListItem $listItem)
    {
        $textObject = $listItem->getTextObject();
        $depth = $listItem->getDepth();
        $listType = $listItem->getStyle()->getListType();
        $styleParagraph = $textObject->getParagraphStyle();

        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:pPr');
        $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph, true);
        $xmlWriter->startElement('w:numPr');
        $xmlWriter->startElement('w:ilvl');
        $xmlWriter->writeAttribute('w:val', $depth);
        $xmlWriter->endElement(); // w:ilvl
        $xmlWriter->startElement('w:numId');
        $xmlWriter->writeAttribute('w:val', $listType);
        $xmlWriter->endElement(); // w:numId
        $xmlWriter->endElement(); // w:numPr
        $xmlWriter->endElement(); // w:pPr
        $this->writeText($xmlWriter, $textObject, true);
        $xmlWriter->endElement(); // w:p
    }

    /**
     * Write footnote reference element
     *
     * @param XMLWriter $xmlWriter
     * @param Table $table
     */
    protected function writeTable(XMLWriter $xmlWriter, Table $table)
    {
        $rows = $table->getRows();
        $cRows = count($rows);

        if ($cRows > 0) {
            $xmlWriter->startElement('w:tbl');

            // Table grid
            $cellWidths = array();
            for ($i = 0; $i < $cRows; $i++) {
                $row = $rows[$i];
                $cells = $row->getCells();
                if (count($cells) <= count($cellWidths)) {
                    continue;
                }
                $cellWidths = array();
                foreach ($cells as $cell) {
                    $cellWidths[] = $cell->getWidth();
                }
            }
            $xmlWriter->startElement('w:tblGrid');
            foreach ($cellWidths as $width) {
                $xmlWriter->startElement('w:gridCol');
                if (!is_null($width)) {
                    $xmlWriter->writeAttribute('w:w', $width);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                }
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement(); // w:tblGrid

            // Table style
            $tblStyle = $table->getStyle();
            $tblWidth = $table->getWidth();
            if ($tblStyle instanceof TableStyle) {
                $this->writeTableStyle($xmlWriter, $tblStyle, false);
            } else {
                if (!empty($tblStyle)) {
                    $xmlWriter->startElement('w:tblPr');
                    $xmlWriter->startElement('w:tblStyle');
                    $xmlWriter->writeAttribute('w:val', $tblStyle);
                    $xmlWriter->endElement();
                    if (!is_null($tblWidth)) {
                        $xmlWriter->startElement('w:tblW');
                        $xmlWriter->writeAttribute('w:w', $tblWidth);
                        $xmlWriter->writeAttribute('w:type', 'pct');
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                }
            }

            // Table rows
            for ($i = 0; $i < $cRows; $i++) {
                $row = $rows[$i];
                $height = $row->getHeight();
                $rowStyle = $row->getStyle();
                $tblHeader = $rowStyle->getTblHeader();
                $cantSplit = $rowStyle->getCantSplit();
                $exactHeight = $rowStyle->getExactHeight();

                $xmlWriter->startElement('w:tr');
                if (!is_null($height) || !is_null($tblHeader) || !is_null($cantSplit)) {
                    $xmlWriter->startElement('w:trPr');
                    if (!is_null($height)) {
                        $xmlWriter->startElement('w:trHeight');
                        $xmlWriter->writeAttribute('w:val', $height);
                        $xmlWriter->writeAttribute('w:hRule', ($exactHeight ? 'exact' : 'atLeast'));
                        $xmlWriter->endElement();
                    }
                    if ($tblHeader) {
                        $xmlWriter->startElement('w:tblHeader');
                        $xmlWriter->writeAttribute('w:val', '1');
                        $xmlWriter->endElement();
                    }
                    if ($cantSplit) {
                        $xmlWriter->startElement('w:cantSplit');
                        $xmlWriter->writeAttribute('w:val', '1');
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                }
                foreach ($row->getCells() as $cell) {
                    $cellStyle = $cell->getStyle();
                    $width = $cell->getWidth();
                    $xmlWriter->startElement('w:tc');
                    $xmlWriter->startElement('w:tcPr');
                    $xmlWriter->startElement('w:tcW');
                    $xmlWriter->writeAttribute('w:w', $width);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement(); // w:tcW
                    if ($cellStyle instanceof Cell) {
                        $this->writeCellStyle($xmlWriter, $cellStyle);
                    }
                    $xmlWriter->endElement(); // w:tcPr
                    $this->writeContainerElements($xmlWriter, $cell);
                    $xmlWriter->endElement(); // w:tc
                }
                $xmlWriter->endElement(); // w:tr
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write image element
     *
     * @param XMLWriter $xmlWriter
     * @param Image $image
     * @param boolean $withoutP
     */
    protected function writeImage(XMLWriter $xmlWriter, Image $image, $withoutP = false)
    {
        $rId = $image->getRelationId();

        $style = $image->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $align = $style->getAlign();
        $marginTop = $style->getMarginTop();
        $marginLeft = $style->getMarginLeft();
        $wrappingStyle = $style->getWrappingStyle();

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');

            if (!is_null($align)) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $align);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }
        $xmlWriter->startElement('w:r');

        $xmlWriter->startElement('w:pict');

        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');

        $imgStyle = '';
        if (null !== $width) {
            $imgStyle .= 'width:' . $width . 'px;';
        }
        if (null !== $height) {
            $imgStyle .= 'height:' . $height . 'px;';
        }
        if (null !== $marginTop) {
            $imgStyle .= 'margin-top:' . $marginTop . 'in;';
        }
        if (null !== $marginLeft) {
            $imgStyle .= 'margin-left:' . $marginLeft . 'in;';
        }

        switch ($wrappingStyle) {
            case ImageStyle::WRAPPING_STYLE_BEHIND:
                $imgStyle .= 'position:absolute;z-index:-251658752;';
                break;
            case ImageStyle::WRAPPING_STYLE_SQUARE:
                $imgStyle .= 'position:absolute;z-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                break;
            case ImageStyle::WRAPPING_STYLE_TIGHT:
                $imgStyle .= 'position:absolute;z-index:251659264;mso-wrap-edited:f;mso-position-horizontal:absolute;mso-position-vertical:absolute';
                break;
            case ImageStyle::WRAPPING_STYLE_INFRONT:
                $imgStyle .= 'position:absolute;zz-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                break;
        }

        $xmlWriter->writeAttribute('style', $imgStyle);

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write watermark element
     *
     * @param XMLWriter $xmlWriter
     * @param Image $image
     */
    protected function writeWatermark(XMLWriter $xmlWriter, Image $image)
    {
        $rId = $image->getRelationId();

        $style = $image->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $marginLeft = $style->getMarginLeft();
        $marginTop = $style->getMarginTop();

        $xmlWriter->startElement('w:p');

        $xmlWriter->startElement('w:r');

        $xmlWriter->startElement('w:pict');

        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');

        $strStyle = 'position:absolute;';
        $strStyle .= ' width:' . $width . 'px;';
        $strStyle .= ' height:' . $height . 'px;';
        if (!is_null($marginTop)) {
            $strStyle .= ' margin-top:' . $marginTop . 'px;';
        }
        if (!is_null($marginLeft)) {
            $strStyle .= ' margin-left:' . $marginLeft . 'px;';
        }

        $xmlWriter->writeAttribute('style', $strStyle);

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write object element
     *
     * @param XMLWriter $xmlWriter
     * @param Object $object
     * @param boolean $withoutP
     */
    protected function writeObject(XMLWriter $xmlWriter, Object $object, $withoutP = false)
    {
        $rIdObject = $object->getRelationId();
        $rIdImage = $object->getImageRelationId();
        $shapeId = md5($rIdObject . '_' . $rIdImage);
        $objectId = $object->getObjectId();
        $style = $object->getStyle();
        $align = $style->getAlign();

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
        }
        if (!is_null($align)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:jc');
            $xmlWriter->writeAttribute('w:val', $align);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:object');
        $xmlWriter->writeAttribute('w:dxaOrig', '249');
        $xmlWriter->writeAttribute('w:dyaOrig', '160');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('id', $shapeId);
        $xmlWriter->writeAttribute('type', '#_x0000_t75');
        $xmlWriter->writeAttribute('style', 'width:104px;height:67px');
        $xmlWriter->writeAttribute('o:ole', '');
        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rIdImage);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement(); // v:imagedata
        $xmlWriter->endElement(); // v:shape
        $xmlWriter->startElement('o:OLEObject');
        $xmlWriter->writeAttribute('Type', 'Embed');
        $xmlWriter->writeAttribute('ProgID', 'Package');
        $xmlWriter->writeAttribute('ShapeID', $shapeId);
        $xmlWriter->writeAttribute('DrawAspect', 'Icon');
        $xmlWriter->writeAttribute('ObjectID', '_' . $objectId);
        $xmlWriter->writeAttribute('r:id', 'rId' . $rIdObject);
        $xmlWriter->endElement(); // o:OLEObject
        $xmlWriter->endElement(); // w:object
        $xmlWriter->endElement(); // w:r
        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write footnote element which links to the actual content in footnotes.xml
     *
     * @param XMLWriter $xmlWriter
     * @param Footnote $footnote
     * @param boolean $withoutP
     */
    protected function writeFootnote(XMLWriter $xmlWriter, Footnote $footnote, $withoutP = false)
    {
        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
        }
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:rStyle');
        $xmlWriter->writeAttribute('w:val', 'FootnoteReference');
        $xmlWriter->endElement(); // w:rStyle
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->startElement('w:footnoteReference');
        $xmlWriter->writeAttribute('w:id', $footnote->getRelationId());
        $xmlWriter->endElement(); // w:footnoteReference
        $xmlWriter->endElement(); // w:r
        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write CheckBox
     *
     * @param boolean $withoutP
     * @param boolean $checkState
     */
    protected function writeCheckBox(XMLWriter $xmlWriter, CheckBox $checkbox, $withoutP = false, $checkState = false)
    {
        $name = htmlspecialchars($checkbox->getName());
        $name = String::controlCharacterPHP2OOXML($name);
        $text = htmlspecialchars($checkbox->getText());
        $text = String::controlCharacterPHP2OOXML($text);
        $styleFont = $checkbox->getFontStyle();
        $styleParagraph = $checkbox->getParagraphStyle();

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
            $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->startElement('w:ffData');
        $xmlWriter->startElement('w:name');
        $xmlWriter->writeAttribute('w:val', $name);
        $xmlWriter->endElement(); //w:name
        $xmlWriter->writeAttribute('w:enabled', '');
        $xmlWriter->startElement('w:calcOnExit');
        $xmlWriter->writeAttribute('w:val', '0');
        $xmlWriter->endElement(); //w:calcOnExit
        $xmlWriter->startElement('w:checkBox');
        $xmlWriter->writeAttribute('w:sizeAuto', '');
        $xmlWriter->startElement('w:default');
        $xmlWriter->writeAttribute('w:val', ($checkState ? '1' : '0'));
        $xmlWriter->endElement(); //w:default
        $xmlWriter->endElement(); //w:checkBox
        $xmlWriter->endElement(); // w:ffData
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw(' FORMCHECKBOX ');
        $xmlWriter->endElement();// w:instrText
        $xmlWriter->endElement(); // w:r
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'seperate');
        $xmlWriter->endElement();// w:fldChar
        $xmlWriter->endElement(); // w:r
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();// w:fldChar
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $this->writeInlineFontStyle($xmlWriter, $styleFont);
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw($text);
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write paragraph style
     *
     * @param XMLWriter $xmlWriter
     * @param Paragraph $style
     * @param bool $withoutPPR
     */
    protected function writeParagraphStyle(XMLWriter $xmlWriter, Paragraph $style, $withoutPPR = false)
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
            if (!is_null($tabs)) {
                $tabs->toXml($xmlWriter);
            }

            if (!$withoutPPR) {
                $xmlWriter->endElement(); // w:pPr
            }
        }
    }

    /**
     * Write font style
     *
     * @param XMLWriter $xmlWriter
     * @param Font $style
     */
    protected function writeFontStyle(XMLWriter $xmlWriter, Font $style)
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
     * @param XMLWriter $xmlWriter
     * @param TableStyle $style
     * @param boolean $isFullStyle
     */
    protected function writeTableStyle(XMLWriter $xmlWriter, TableStyle $style, $isFullStyle = true)
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
     * @param XMLWriter $xmlWriter
     * @param string $type
     * @param TableStyle $style
     */
    protected function writeRowStyle(XMLWriter $xmlWriter, $type, TableStyle $style)
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
     * @param XMLWriter $xmlWriter
     * @param Cell $style
     */
    protected function writeCellStyle(XMLWriter $xmlWriter, Cell $style)
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
     * Write media rels (image, embeddings, hyperlink)
     *
     * @param XMLWriter $xmlWriter
     * @param array mediaRels
     */
    protected function writeMediaRels(XMLWriter $xmlWriter, $mediaRels)
    {
        $rTypePrefix = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';
        foreach ($mediaRels as $mediaRel) {
            $rId = $mediaRel['rID'];
            $rType = $mediaRel['type'];
            $rName = $mediaRel['target']; // file name
            $targetMode = ($rType == 'hyperlink') ? 'External' : '';
            $rType = $rTypePrefix . ($rType == 'embeddings' ? 'oleObject' : $rType);
            $this->writeRel($xmlWriter, $rId, $rType, $rName, $targetMode);
        }

    }

    /**
     * Write individual rels entry
     *
     * @param XMLWriter $xmlWriter
     * @param int $pId Relationship ID
     * @param string $pType Relationship type
     * @param string $pTarget Relationship target
     * @param string $pTargetMode Relationship target mode
     */
    protected function writeRel(XMLWriter $xmlWriter, $pId, $pType, $pTarget, $pTargetMode = '')
    {
        if ($pType != '' && $pTarget != '') {
            if (strpos($pId, 'rId') === false) {
                $pId = 'rId' . $pId;
            }
            $xmlWriter->startElement('Relationship');
            $xmlWriter->writeAttribute('Id', $pId);
            $xmlWriter->writeAttribute('Type', $pType);
            $xmlWriter->writeAttribute('Target', $pTarget);
            if ($pTargetMode != '') {
                $xmlWriter->writeAttribute('TargetMode', $pTargetMode);
            }
            $xmlWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }

    /**
     * Write inline paragraph style
     *
     * @param XMLWriter $xmlWriter
     * @param Paragraph|string $styleParagraph
     * @param boolean $withoutPPR
     */
    protected function writeInlineParagraphStyle(XMLWriter $xmlWriter, $styleParagraph = null, $withoutPPR = false)
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
     * @param XMLWriter $xmlWriter
     * @param Font|string $styleFont
     */
    protected function writeInlineFontStyle(XMLWriter $xmlWriter, $styleFont = null)
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
     * @param XMLWriter $xmlWriter
     * @param Container $container
     * @param Container $textBreak Add text break when no element found
     */
    protected function writeContainerElements(XMLWriter $xmlWriter, Container $container)
    {
        // Check allowed elements
        $elmCommon = array('Text', 'Link', 'TextBreak', 'Image');
        $elmMainCell = array_merge($elmCommon, array('TextRun', 'ListItem', 'CheckBox'));
        $allowedElements = array(
            'Section'  => array_merge($elmMainCell, array('Table', 'Footnote', 'Object', 'Title', 'PageBreak', 'TOC')),
            'Header'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Footer'   => array_merge($elmMainCell, array('Table', 'PreserveText')),
            'Cell'     => array_merge($elmMainCell, array('Object', 'PreserveText', 'Footnote')),
            'TextRun'  => array_merge($elmCommon, array('Object', 'Footnote')),
            'Footnote' => array_merge($elmCommon, array('Object')),
        );
        $containerName = get_class($container);
        $containerName = substr($containerName, strrpos($containerName, '\\') + 1);
        if (array_key_exists($containerName, $allowedElements)) {
            $containerElements = $allowedElements[$containerName];
        } else {
            throw new Exception('Invalid container.');
        }

        // Loop through elements
        $elements = $container->getElements();
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                $elmName = get_class($element);
                $elmName = substr($elmName, strrpos($elmName, '\\') + 1);
                if (in_array($elmName, $containerElements)) {
                    $method = "write{$elmName}";
                    // Image on Header could be watermark
                    if ($containerName == 'Header' && $elmName == 'Image') {
                        if ($element->getIsWatermark()) {
                            $method = "writeWatermark";
                        }
                    }
                    if (in_array($containerName, array('TextRun', 'Footnote'))) {
                        $this->$method($xmlWriter, $element, true);
                    } else {
                        $this->$method($xmlWriter, $element);
                    }
                }
            }
        } else {
            if ($containerName == 'Cell') {
                $this->writeTextBreak($xmlWriter);
            }
        }
    }

    /**
     * Write margin or border
     *
     * @param XMLWriter $xmlWriter
     * @param boolean $isBorder
     * @param array $sizes
     * @param array $colors
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
                        if (array_key_exists('defaultColor', $attributes))
                            $colors[$i] = $attributes['defaultColor'];

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
