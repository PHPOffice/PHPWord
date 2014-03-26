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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Section\Table;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Exceptions\InvalidObjectException;
use PhpOffice\PhpWord\Exceptions\InvalidImageException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Section\Footer\PreserveText;
use PhpOffice\PhpWord\Section\Image;
use PhpOffice\PhpWord\Section\Link;
use PhpOffice\PhpWord\Section\ListItem;
use PhpOffice\PhpWord\Section\Object;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Section\TextRun;
use PhpOffice\PhpWord\Shared\String;

/**
 * Table cell element
 */
class Cell
{
    /**
     * Cell Width
     *
     * @var int
     */
    private $_width = null;

    /**
     * Cell Style
     *
     * @var \PhpOffice\PhpWord\Style\Cell
     */
    private $_style;

    /**
     * Cell Element Collection
     *
     * @var array
     */
    private $_elementCollection = array();

    /**
     * Table holder
     *
     * @var string
     */
    private $_insideOf;

    /**
     * Section/Header/Footer count
     *
     * @var int
     */
    private $_pCount;


    /**
     * Create a new Table Cell
     *
     * @param string $insideOf
     * @param int $pCount
     * @param int $width
     * @param mixed $style
     */
    public function __construct($insideOf, $pCount, $width = null, $style = null)
    {
        $this->_insideOf = $insideOf;
        $this->_pCount = $pCount;
        $this->_width = $width;
        $this->_style = new \PhpOffice\PhpWord\Style\Cell();

        if (!is_null($style)) {
            if (is_array($style)) {
                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->_style->setStyleValue($key, $value);
                }
            } else {
                $this->_style = $style;
            }
        }
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
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $text = new Text($text, $styleFont, $styleParagraph);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add a Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Link
     */
    public function addLink($linkSrc, $linkName = null, $style = null)
    {
        if ($this->_insideOf == 'section') {
            if (!String::isUTF8($linkSrc)) {
                $linkSrc = utf8_encode($linkSrc);
            }
            if (!is_null($linkName)) {
                if (!String::isUTF8($linkName)) {
                    $linkName = utf8_encode($linkName);
                }
            }

            $link = new Link($linkSrc, $linkName, $style);
            $rID = Media::addSectionLinkElement($linkSrc);
            $link->setRelationId($rID);

            $this->_elementCollection[] = $link;
            return $link;
        } else {
            throw new Exception('Unsupported Link header / footer reference');
            return false;
        }
    }

    /**
     * Add TextBreak
     *
     * @param int $count
     * @param null|string|array|\PhpOffice\PhpWord\Style\Font $fontStyle
     * @param null|string|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->_elementCollection[] = new TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Add a ListItem Element
     *
     * @param string $text
     * @param int $depth
     * @param mixed $styleText
     * @param mixed $styleList
     * @return \PhpOffice\PhpWord\Section\ListItem
     */
    public function addListItem($text, $depth = 0, $styleText = null, $styleList = null)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $listItem = new ListItem($text, $depth, $styleText, $styleList);
        $this->_elementCollection[] = $listItem;
        return $listItem;
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
            if ($this->_insideOf == 'section') {
                $rID = Media::addSectionMediaElement($src, 'image', $image);
            } elseif ($this->_insideOf == 'header') {
                $rID = Media::addHeaderMediaElement($this->_pCount, $src, $image);
            } elseif ($this->_insideOf == 'footer') {
                $rID = Media::addFooterMediaElement($this->_pCount, $src, $image);
            }
            $image->setRelationId($rID);
            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new InvalidImageException;
        }
    }

    /**
     * Add a by PHP created Image Element
     *
     * @param string $link
     * @param mixed $style
     * @deprecated
     */
    public function addMemoryImage($src, $style = null)
    {
        return $this->addImage($src, $style);
    }

    /**
     * Add a OLE-Object Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Object
     */
    public function addObject($src, $style = null)
    {
        $object = new Object($src, $style);

        if (!is_null($object->getSource())) {
            $inf = pathinfo($src);
            $ext = $inf['extension'];
            if (strlen($ext) == 4 && strtolower(substr($ext, -1)) == 'x') {
                $ext = substr($ext, 0, -1);
            }

            $iconSrc = __DIR__ . '/../../_staticDocParts/';
            if (!\file_exists($iconSrc . '_' . $ext . '.png')) {
                $iconSrc = $iconSrc . '_default.png';
            } else {
                $iconSrc .= '_' . $ext . '.png';
            }

            $rIDimg = Media::addSectionMediaElement($iconSrc, 'image', new Image($iconSrc));
            $data = Media::addSectionMediaElement($src, 'oleObject');
            $rID = $data[0];
            $objectId = $data[1];

            $object->setRelationId($rID);
            $object->setObjectId($objectId);
            $object->setImageRelationId($rIDimg);

            $this->_elementCollection[] = $object;
            return $object;
        } else {
            throw new InvalidObjectException;
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
        if ($this->_insideOf == 'footer' || $this->_insideOf == 'header') {
            if (!String::isUTF8($text)) {
                $text = utf8_encode($text);
            }
            $ptext = new PreserveText($text, $styleFont, $styleParagraph);
            $this->_elementCollection[] = $ptext;
            return $ptext;
        } else {
            throw new Exception('addPreserveText only supported in footer/header.');
        }
    }

    /**
     * Create a new TextRun
     *
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\TextRun
     */
    public function createTextRun($styleParagraph = null)
    {
        $textRun = new TextRun($styleParagraph);
        $this->_elementCollection[] = $textRun;
        return $textRun;
    }

    /**
     * Get all Elements
     *
     * @return array
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get Cell Style
     *
     * @return \PhpOffice\PhpWord\Style\Cell
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Get Cell width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
}
