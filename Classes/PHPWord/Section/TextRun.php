<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * PHPWord_Section_TextRun
 */
class PHPWord_Section_TextRun
{

    /**
     * Paragraph style
     *
     * @var PHPWord_Style_Font
     */
    private $_styleParagraph;

    /**
     * Text collection
     *
     * @var array
     */
    private $_elementCollection;


    /**
     * Create a new TextRun Element
     */
    public function __construct($styleParagraph = null)
    {
        $this->_elementCollection = array();

        // Set paragraph style
        if (is_array($styleParagraph)) {
            $this->_styleParagraph = new PHPWord_Style_Paragraph();

            foreach ($styleParagraph as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleParagraph->setStyleValue($key, $value);
            }
        } else {
            $this->_styleParagraph = $styleParagraph;
        }
    }


    /**
     * Add a Text Element
     *
     * @var string $text
     * @var mixed $styleFont
     * @return PHPWord_Section_Text
     */
    public function addText($text = null, $styleFont = null)
    {
        if (!PHPWord_Shared_String::IsUTF8($text)) {
            $text = utf8_encode($text);
        }
        $text = new PHPWord_Section_Text($text, $styleFont);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add a Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $styleFont
     * @return PHPWord_Section_Link
     */
    public function addLink($linkSrc, $linkName = null, $styleFont = null)
    {
        $linkSrc = utf8_encode($linkSrc);
        if (!is_null($linkName)) {
            $linkName = utf8_encode($linkName);
        }

        $link = new PHPWord_Section_Link($linkSrc, $linkName, $styleFont);
        $rID = PHPWord_Media::addSectionLinkElement($linkSrc);
        $link->setRelationId($rID);

        $this->_elementCollection[] = $link;
        return $link;
    }

    /**
     * Add a Image Element
     *
     * @param string $imageSrc
     * @param mixed $styleFont
     * @return PHPWord_Section_Image
     */
    public function addImage($imageSrc, $style = null)
    {
        $image = new PHPWord_Section_Image($imageSrc, $style);

        if (!is_null($image->getSource())) {
            $rID = PHPWord_Media::addSectionMediaElement($imageSrc, 'image');
            $image->setRelationId($rID);

            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new Exception('Source does not exist or unsupported image type.');
        }
    }

    /**
     * Add TextBreak
     *
     * @param   int $count
     * @param   null|string|array|PHPWord_Style_Font        $fontStyle
     * @param   null|string|array|PHPWord_Style_Paragraph   $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->_elementCollection[] = new PHPWord_Section_TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Create a new Footnote Element
     *
     * @param string $text
     * @return PHPWord_Section_Footnote
     */
    public function createFootnote($styleParagraph = null)
    {
        $footnote = new PHPWord_Section_Footnote($styleParagraph);
        $refID = PHPWord_Footnote::addFootnoteElement($footnote);
        $footnote->setReferenceId($refID);
        $this->_elementCollection[] = $footnote;
        return $footnote;
    }

    /**
     * Get TextRun content
     *
     * @return string
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get Paragraph style
     *
     * @return PHPWord_Style_Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_styleParagraph;
    }
}
