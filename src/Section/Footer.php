<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
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
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Section\Footer\PreserveText;
use PhpOffice\PhpWord\Shared\String;

class Footer
{
    /**
     * Footer Count
     *
     * @var int
     */
    private $_footerCount;

    /**
     * Footer Relation ID
     *
     * @var int
     */
    private $_rId;

    /**
     * Footer Element Collection
     *
     * @var int
     */
    private $_elementCollection = array();

    /**
     * Create a new Footer
     */
    public function __construct($sectionCount)
    {
        $this->_footerCount = $sectionCount;
    }

    /**
     * Add a Text Element
     *
     * @param string $text
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Text
     */
    public function addText($text, $styleFont = null, $styleParagraph = null)
    {
        if (!String::IsUTF8($text)) {
            $text = utf8_encode($text);
        }
        $text = new Text($text, $styleFont, $styleParagraph);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add TextBreak
     *
     * @param int $count
     * @param null|string|array|\PhpOffice\PhpWord\Style\Font      $fontStyle
     * @param null|string|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->_elementCollection[] = new TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Create a new TextRun
     *
     * @return \PhpOffice\PhpWord\Section\TextRun
     */
    public function createTextRun($styleParagraph = null)
    {
        $textRun = new TextRun($styleParagraph);
        $this->_elementCollection[] = $textRun;
        return $textRun;
    }

    /**
     * Add a Table Element
     *
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Table
     */
    public function addTable($style = null)
    {
        $table = new Table('footer', $this->_footerCount, $style);
        $this->_elementCollection[] = $table;
        return $table;
    }

    /**
     * Add a Image Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Image
     */
    public function addImage($src, $style = null)
    {
        $image = new Image($src, $style);

        if (!is_null($image->getSource())) {
            $rID = Media::addFooterMediaElement($this->_footerCount, $src);
            $image->setRelationId($rID);

            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new Exception('Src does not exist or invalid image type.');
        }
    }

    /**
     * Add a by PHP created Image Element
     *
     * @param string $link
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\MemoryImage
     */
    public function addMemoryImage($link, $style = null)
    {
        $memoryImage = new MemoryImage($link, $style);
        if (!is_null($memoryImage->getSource())) {
            $rID = Media::addFooterMediaElement($this->_footerCount, $link, $memoryImage);
            $memoryImage->setRelationId($rID);

            $this->_elementCollection[] = $memoryImage;
            return $memoryImage;
        } else {
            throw new Exception('Unsupported image type.');
        }
    }

    /**
     * Add a PreserveText Element
     *
     * @param string $text
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Footer\PreserveText
     */
    public function addPreserveText($text, $styleFont = null, $styleParagraph = null)
    {
        if (!String::IsUTF8($text)) {
            $text = utf8_encode($text);
        }
        $ptext = new PreserveText($text, $styleFont, $styleParagraph);
        $this->_elementCollection[] = $ptext;
        return $ptext;
    }

    /**
     * Get Footer Relation ID
     */
    public function getRelationId()
    {
        return $this->_rId;
    }

    /**
     * Set Footer Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Get all Footer Elements
     * @return array
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get Footer Count
     */
    public function getFooterCount()
    {
        return $this->_footerCount;
    }
}
