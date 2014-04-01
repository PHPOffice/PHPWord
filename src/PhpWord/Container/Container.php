<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Container;

use PhpOffice\PhpWord\Exception\InvalidImageException;
use PhpOffice\PhpWord\Exception\InvalidObjectException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\TOC;
use PhpOffice\PhpWord\Footnote as FootnoteCollection;
use PhpOffice\PhpWord\Shared\String;
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
use PhpOffice\PhpWord\Element\Footnote as FootnoteElement;
use PhpOffice\PhpWord\Element\CheckBox;

/**
 * Container abstract class
 *
 * @since 0.9.2
 */
abstract class Container
{
    /**
     * Container type section|header|footer
     *
     * @var string
     */
    protected $containerType;

    /**
     * Section Id
     *
     * @var int
     */
    protected $sectionId;

    /**
     * Footer Element Collection
     *
     * @var int
     */
    protected $elements = array();

    /**
     * Document part type: section|header|footer
     *
     * Used by textrun and cell to determine where the element is located
     * because it will affect the availability of other element, e.g. footnote
     * will not be available when $docPartType is header or footer.
     *
     * @var string
     */
    protected $docPartType = null;

    /**
     * Document part Id
     *
     * @var int
     */
    protected $docPartId;

    /**
     * Relation Id
     *
     * @var int
     */
    private $relationId;

    /**
     * Add text element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return Text
     */
    public function addText($text, $fontStyle = null, $paragraphStyle = null)
    {
        if (in_array($this->containerType, array('footnote', 'textrun'))) {
            $paragraphStyle = null;
        }
        $text = String::toUTF8($text);
        $element = new Text($text, $fontStyle, $paragraphStyle);
        $this->elements[] = $element;

        return $element;
    }

    /**
     * Add text break element
     *
     * @param int $count
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->elements[] = new TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Add textrun element
     *
     * @param mixed $paragraphStyle
     * @return TextRun
     */
    public function addTextRun($paragraphStyle = null)
    {
        if (!in_array($this->containerType, array('section', 'header', 'footer', 'cell'))) {
            throw new \BadMethodCallException();
        }
        if ($this->containerType == 'cell') {
            $docPartType = $this->docPartType;
            $docPartId = $this->docPartId;
        } else {
            $docPartType = $this->containerType;
            $docPartId = $this->sectionId;
        }

        $textRun = new TextRun($paragraphStyle, $docPartType, $docPartId);
        $this->elements[] = $textRun;

        return $textRun;
    }

    /**
     * Add link element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return Link
     */
    public function addLink($linkSrc, $linkName = null, $fontStyle = null, $paragraphStyle = null)
    {
        if (!is_null($this->docPartType)) {
            $linkContainer = $this->docPartType;
            $linkContainerId = $this->docPartId;
        } else {
            $linkContainer = $this->containerType;
            $linkContainerId = $this->sectionId;
        }
        if ($linkContainer == 'header' || $linkContainer == 'footer') {
            $linkContainer .= $linkContainerId;
        }

        $linkSrc = String::toUTF8($linkSrc);
        $linkName = String::toUTF8($linkName);
        $link = new Link($linkSrc, $linkName, $fontStyle, $paragraphStyle);
        if ($linkContainer == 'section') {
            $rID = Media::addSectionLinkElement($linkSrc);
        } else {
            $rID = Media::addMediaElement($linkContainer, 'hyperlink', $linkSrc);
        }
        $link->setRelationId($rID);
        $this->elements[] = $link;

        return $link;
    }

    /**
     * Add a Title Element
     *
     * @param string $text
     * @param int $depth
     * @return Title
     * @todo Enable title element in header, footer, footnote, textrun
     */
    public function addTitle($text, $depth = 1)
    {
        if (!in_array($this->containerType, array('section'))) {
            throw new \BadMethodCallException();
        }

        $text = String::toUTF8($text);
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
        $this->elements[] = $title;

        return $title;
    }

    /**
     * Add preserve text element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return PreserveText
     */
    public function addPreserveText($text, $fontStyle = null, $paragraphStyle = null)
    {
        if (!in_array($this->containerType, array('header', 'footer', 'cell'))) {
            throw new \BadMethodCallException();
        }
        if ($this->containerType == 'cell' && $this->docPartType == 'section') {
            throw new \BadMethodCallException();
        }

        $text = String::toUTF8($text);
        $ptext = new PreserveText($text, $fontStyle, $paragraphStyle);
        $this->elements[] = $ptext;

        return $ptext;
    }

    /**
     * Add listitem element
     *
     * @param string $text
     * @param int $depth
     * @param mixed $fontStyle
     * @param mixed $styleList
     * @param mixed $paragraphStyle
     * @return ListItem
     * @todo Enable list item element in header and footer
     */
    public function addListItem($text, $depth = 0, $fontStyle = null, $styleList = null, $paragraphStyle = null)
    {
        if (!in_array($this->containerType, array('section', 'header', 'footer', 'cell'))) {
            throw new \BadMethodCallException();
        }

        $text = String::toUTF8($text);
        $listItem = new ListItem($text, $depth, $fontStyle, $styleList, $paragraphStyle);
        $this->elements[] = $listItem;

        return $listItem;
    }

    /**
     * Add table element
     *
     * @param mixed $style
     * @return Table
     */
    public function addTable($style = null)
    {
        if (!in_array($this->containerType, array('section', 'header', 'footer'))) {
            throw new \BadMethodCallException();
        }

        $table = new Table($this->containerType, $this->sectionId, $style);
        $this->elements[] = $table;

        return $table;
    }

    /**
     * Add image element
     *
     * @param string $src
     * @param mixed $style Image style
     * @param boolean $isWatermark
     * @return Image
     */
    public function addImage($src, $style = null, $isWatermark = false)
    {
        if ($this->containerType == 'cell') {
            $imageContainerType = $this->docPartType;
            $imageContainerId = $this->docPartId;
        } else {
            $imageContainerType = $this->containerType;
            $imageContainerId = $this->sectionId;
        }

        $image = new Image($src, $style, $isWatermark);
        if (!is_null($image->getSource())) {
            $rID = null;
            switch ($imageContainerType) {
                case 'textrun':
                case 'section':
                    $rID = Media::addSectionMediaElement($src, 'image', $image);
                    break;
                case 'header':
                    $rID = Media::addHeaderMediaElement($imageContainerId, $src, $image);
                    break;
                case 'footer':
                    $rID = Media::addFooterMediaElement($imageContainerId, $src, $image);
                    break;
                case 'footnote':
                    $rID = Media::addMediaElement('footnotes', 'image', $src, $image);
                    break;
            }
            $image->setRelationId($rID);
            $this->elements[] = $image;
            return $image;
        } else {
            throw new InvalidImageException;
        }
    }

    /**
     * Add OLE-object element
     *
     * All exceptions should be handled by PhpOffice\PhpWord\Element\Object
     *
     * @param string $src
     * @param mixed $style
     * @return Object
     * @todo Enable OLE object element in header and footer
     */
    public function addObject($src, $style = null)
    {
        if (!in_array($this->containerType, array('section', 'cell'))) {
            throw new \BadMethodCallException();
        }
        if ($this->containerType == 'cell' && $this->docPartType != 'section') {
            throw new \BadMethodCallException();
        }

        $object = new Object($src, $style);
        if (!is_null($object->getSource())) {
            $inf = pathinfo($src);
            $ext = $inf['extension'];
            if (strlen($ext) == 4 && strtolower(substr($ext, -1)) == 'x') {
                $ext = substr($ext, 0, -1);
            }
            $icon = __DIR__ . "/../_staticDocParts/_{$ext}.png";
            $rIDimg = Media::addSectionMediaElement($icon, 'image', new Image($icon));
            $data = Media::addSectionMediaElement($src, 'oleObject');
            $rID = $data[0];
            $objectId = $data[1];
            $object->setRelationId($rID);
            $object->setObjectId($objectId);
            $object->setImageRelationId($rIDimg);
            $this->elements[] = $object;
            return $object;
        } else {
            throw new InvalidObjectException();
        }
    }

    /**
     * Add footnote element
     *
     * @param mixed $paragraphStyle
     * @return FootnoteElement
     */
    public function addFootnote($paragraphStyle = null)
    {
        if (!in_array($this->containerType, array('section', 'textrun', 'cell'))) {
            throw new \BadMethodCallException();
        }
        if (!is_null($this->docPartType) && $this->docPartType != 'section') {
            throw new \BadMethodCallException();
        }

        $footnote = new FootnoteElement($paragraphStyle);
        $refID = FootnoteCollection::addFootnoteElement($footnote);
        $footnote->setReferenceId($refID);
        $this->elements[] = $footnote;

        return $footnote;
    }

    /**
     * Add a CheckBox Element
     *
     * @param string $name
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return CheckBox
     * @todo Enable checkbox element in header and footer
     */
    public function addCheckBox($name, $text, $fontStyle = null, $paragraphStyle = null)
    {
        if (!in_array($this->containerType, array('section', 'header', 'footer', 'cell'))) {
            throw new \BadMethodCallException();
        }
        if ($this->containerType == 'cell' && $this->docPartType != 'section') {
            throw new \BadMethodCallException();
        }

        $name = String::toUTF8($name);
        $text = String::toUTF8($text);
        $element = new CheckBox($name, $text, $fontStyle, $paragraphStyle);
        $this->elements[] = $element;

        return $element;
    }

    /**
     * Get section number
     * getFooterCount
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Get all elements
     *
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Get relation Id
     *
     * @return int
     */
    public function getRelationId()
    {
        if (!in_array($this->containerType, array('header', 'footer'))) {
            throw new \BadMethodCallException();
        }

        return $this->relationId;
    }

    /**
     * Set relation Id
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        if (!in_array($this->containerType, array('header', 'footer'))) {
            throw new \BadMethodCallException();
        }

        $this->relationId = $rId;
    }

    /**
     * Add memory image element
     *
     * @param string $src
     * @param mixed $style
     * @deprecated 0.9.0
     */
    public function addMemoryImage($src, $style = null)
    {
        return $this->addImage($src, $style);
    }

    /**
     * Create textrun element
     *
     * @param mixed $paragraphStyle
     * @deprecated 0.9.2
     */
    public function createTextRun($paragraphStyle = null)
    {
        return $this->addTextRun($paragraphStyle);
    }

    /**
     * Create footnote element
     *
     * @param mixed $paragraphStyle
     * @deprecated 0.9.2
     */
    public function createFootnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle);
    }
}
