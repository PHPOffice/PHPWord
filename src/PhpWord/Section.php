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

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exceptions\InvalidImageException;
use PhpOffice\PhpWord\Exceptions\InvalidObjectException;
use PhpOffice\PhpWord\Section\Footer;
use PhpOffice\PhpWord\Section\Image;
use PhpOffice\PhpWord\Section\Header;
use PhpOffice\PhpWord\Section\Link;
use PhpOffice\PhpWord\Section\ListItem;
use PhpOffice\PhpWord\Section\Object;
use PhpOffice\PhpWord\Section\PageBreak;
use PhpOffice\PhpWord\Section\Settings;
use PhpOffice\PhpWord\Section\Table;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Section\TextRun;
use PhpOffice\PhpWord\Section\Title;
use PhpOffice\PhpWord\Shared\String;

/**
 * Section
 */
class Section
{
    /**
     * Section count
     *
     * @var int
     */
    private $_sectionCount;

    /**
     * Section settings
     *
     * @var \PhpOffice\PhpWord\Section\Settings
     */
    private $_settings;

    /**
     * Section Element Collection
     *
     * @var array
     */
    private $_elementCollection = array();

    /**
     * Section Headers
     *
     * @var array
     */
    private $_headers = array();

    /**
     * Section Footer
     *
     * @var \PhpOffice\PhpWord\Section\Footer
     */
    private $_footer = null;


    /**
     * Create a new Section
     *
     * @param int $sectionCount
     * @param mixed $settings
     */
    public function __construct($sectionCount, $settings = null)
    {
        $this->_sectionCount = $sectionCount;
        $this->_settings = new Settings();
        $this->setSettings($settings);
    }

    /**
     * Set Section Settings
     *
     * @param   array $settings
     */
    public function setSettings($settings = null)
    {
        if (!is_null($settings) && is_array($settings)) {
            foreach ($settings as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_settings->setSettingValue($key, $value);
            }
        }
    }

    /**
     * Get Section Settings
     *
     * @return \PhpOffice\PhpWord\Section\Settings
     */
    public function getSettings()
    {
        return $this->_settings;
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
     * Add a Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Link
     */
    public function addLink($linkSrc, $linkName = null, $styleFont = null, $styleParagraph = null)
    {
        if (!String::IsUTF8($linkSrc)) {
            $linkSrc = utf8_encode($linkSrc);
        }
        if (!is_null($linkName)) {
            if (!String::IsUTF8($linkName)) {
                $linkName = utf8_encode($linkName);
            }
        }

        $link = new Link($linkSrc, $linkName, $styleFont, $styleParagraph);
        $rID = Media::addSectionLinkElement($linkSrc);
        $link->setRelationId($rID);

        $this->_elementCollection[] = $link;
        return $link;
    }

    /**
     * Add a TextBreak Element
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
     * Add a PageBreak Element
     */
    public function addPageBreak()
    {
        $this->_elementCollection[] = new PageBreak();
    }

    /**
     * Add a Table Element
     *
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Table
     */
    public function addTable($style = null)
    {
        $table = new Table('section', $this->_sectionCount, $style);
        $this->_elementCollection[] = $table;
        return $table;
    }

    /**
     * Add a ListItem Element
     *
     * @param string $text
     * @param int $depth
     * @param mixed $styleFont
     * @param mixed $styleList
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\ListItem
     */
    public function addListItem($text, $depth = 0, $styleFont = null, $styleList = null, $styleParagraph = null)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $listItem = new ListItem($text, $depth, $styleFont, $styleList, $styleParagraph);
        $this->_elementCollection[] = $listItem;
        return $listItem;
    }

    /**
     * Add a OLE-Object Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Object
     * @throws \PhpOffice\PhpWord\Exceptions\InvalidObjectException
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

            $iconSrc = __DIR__ . '/_staticDocParts/';
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
     * Add a Image Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Image
     * @throws \PhpOffice\PhpWord\Exceptions\InvalidImageException
     */
    public function addImage($src, $style = null)
    {
        $image = new Image($src, $style);
        if (!is_null($image->getSource())) {
            $rID = Media::addSectionMediaElement($src, 'image', $image);
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
     * Add a Table-of-Contents Element
     *
     * @param mixed $styleFont
     * @param mixed $styleTOC
     * @return \PhpOffice\PhpWord\TOC
     */
    public function addTOC($styleFont = null, $styleTOC = null)
    {
        $toc = new TOC($styleFont, $styleTOC);
        $this->_elementCollection[] = $toc;
        return $toc;
    }

    /**
     * Add a Title Element
     *
     * @param string $text
     * @param int $depth
     * @return \PhpOffice\PhpWord\Section\Title
     */
    public function addTitle($text, $depth = 1)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $styles = Style::getStyles();
        if (array_key_exists('Heading_' . $depth, $styles)) {
            $style = 'Heading' . $depth;
        } else {
            $style = null;
        }

        $title = new Title($text, $depth, $style);

        $data = TOC::addTitle($text, $depth);
        $anchor = $data[0];
        $bookmarkId = $data[1];

        $title->setAnchor($anchor);
        $title->setBookmarkId($bookmarkId);

        $this->_elementCollection[] = $title;
        return $title;
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
     * Create a new Header
     *
     * @return \PhpOffice\PhpWord\Section\Header
     */
    public function createHeader()
    {
        $header = new Header($this->_sectionCount);
        $this->_headers[] = $header;
        return $header;
    }

    /**
     * Get Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * Is there a header for this section that is for the first page only?
     *
     * If any of the Header instances have a type of Header::FIRST then this method returns true.
     * False otherwise.
     *
     * @return Boolean
     */
    public function hasDifferentFirstPage()
    {
        $value = array_filter($this->_headers, function (Header &$header) {
            return $header->getType() == Header::FIRST;
        });
        return count($value) > 0;
    }

    /**
     * Create a new Footer
     *
     * @return \PhpOffice\PhpWord\Section\Footer
     */
    public function createFooter()
    {
        $footer = new Footer($this->_sectionCount);
        $this->_footer = $footer;
        return $footer;
    }

    /**
     * Get footer element
     *
     * @return \PhpOffice\PhpWord\Section\Footer
     */
    public function getFooter()
    {
        return $this->_footer;
    }

    /**
     * Create a new Footnote Element
     *
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Footnote
     */
    public function createFootnote($styleParagraph = null)
    {
        $footnote = new \PhpOffice\PhpWord\Section\Footnote($styleParagraph);
        $refID = Footnote::addFootnoteElement($footnote);
        $footnote->setReferenceId($refID);
        $this->_elementCollection[] = $footnote;
        return $footnote;
    }
}
