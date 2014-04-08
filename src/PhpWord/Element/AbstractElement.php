<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\InvalidObjectException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\TOC;
use PhpOffice\PhpWord\Footnote as FootnoteCollection;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Element\Element;
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
abstract class AbstractElement
{
    /**
     * Container type section|header|footer|cell|textrun|footnote
     *
     * @var string
     */
    protected $container;

    /**
     * Section Id
     *
     * @var int
     */
    protected $sectionId;

    /**
     * Document part type: section|header|footer
     *
     * Used by textrun and cell container to determine where the element is
     * located because it will affect the availability of other element,
     * e.g. footnote will not be available when $docPart is header or footer.
     *
     * @var string
     */
    private $docPart = 'section';

    /**
     * Document part Id
     *
     * For header and footer, this will be = ($sectionId - 1) * 3 + $index
     * because the max number of header/footer in every page is 3, i.e.
     * AUTO, FIRST, and EVEN (AUTO = ODD)
     *
     * @var integer
     */
    private $docPartId = 1;

    /**
     * Elements collection
     *
     * @var array
     */
    protected $elements = array();

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
        $this->checkValidity('text');

        // Reset paragraph style for footnote and textrun. They have their own
        if (in_array($this->container, array('footnote', 'textrun'))) {
            $paragraphStyle = null;
        }

        $text = String::toUTF8($text);
        $textObject = new Text($text, $fontStyle, $paragraphStyle);
        $textObject->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->elements[] = $textObject;

        return $textObject;
    }

    /**
     * Add textrun element
     *
     * @param mixed $paragraphStyle
     * @return TextRun
     */
    public function addTextRun($paragraphStyle = null)
    {
        $this->checkValidity('textrun');

        $textRun = new TextRun($paragraphStyle);
        $textRun->setDocPart($this->getDocPart(), $this->getDocPartId());
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
        $this->checkValidity('link');
        $elementDocPart = $this->checkElementDocPart();

        $link = new Link(String::toUTF8($linkSrc), String::toUTF8($linkName), $fontStyle, $paragraphStyle);
        $link->setDocPart($this->getDocPart(), $this->getDocPartId());
        $rID = Media::addElement($elementDocPart, 'link', $linkSrc);
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
     * @todo Enable title element in other containers
     */
    public function addTitle($text, $depth = 1)
    {
        $this->checkValidity('title');

        $styles = Style::getStyles();
        if (array_key_exists('Heading_' . $depth, $styles)) {
            $style = 'Heading' . $depth;
        } else {
            $style = null;
        }
        $text = String::toUTF8($text);
        $title = new Title($text, $depth, $style);
        $title->setDocPart($this->getDocPart(), $this->getDocPartId());
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
        $this->checkValidity('preservetext');

        $preserveText = new PreserveText(String::toUTF8($text), $fontStyle, $paragraphStyle);
        $preserveText->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->elements[] = $preserveText;

        return $preserveText;
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
        $this->checkValidity('textbreak');

        for ($i = 1; $i <= $count; $i++) {
            $textBreak = new TextBreak($fontStyle, $paragraphStyle);
            $textBreak->setDocPart($this->getDocPart(), $this->getDocPartId());
            $this->elements[] = $textBreak;
        }
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
     */
    public function addListItem($text, $depth = 0, $fontStyle = null, $styleList = null, $paragraphStyle = null)
    {
        $this->checkValidity('listitem');

        $listItem = new ListItem(String::toUTF8($text), $depth, $fontStyle, $styleList, $paragraphStyle);
        $listItem->setDocPart($this->getDocPart(), $this->getDocPartId());
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
        $this->checkValidity('table');

        $table = new Table($this->getDocPart(), $this->getDocPartId(), $style);
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
        $this->checkValidity('image');
        $elementDocPart = $this->checkElementDocPart();

        $image = new Image($src, $style, $isWatermark);
        $image->setDocPart($this->getDocPart(), $this->getDocPartId());
        $rID = Media::addElement($elementDocPart, 'image', $src, $image);
        $image->setRelationId($rID);
        $this->elements[] = $image;
        return $image;
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
        $this->checkValidity('object');
        $elementDocPart = $this->checkElementDocPart();

        $object = new Object($src, $style);
        $object->setDocPart($this->getDocPart(), $this->getDocPartId());
        if (!is_null($object->getSource())) {
            $inf = pathinfo($src);
            $ext = $inf['extension'];
            if (strlen($ext) == 4 && strtolower(substr($ext, -1)) == 'x') {
                $ext = substr($ext, 0, -1);
            }
            $icon = realpath(__DIR__ . "/../_staticDocParts/_{$ext}.png");
            $rID = Media::addElement($elementDocPart, 'object', $src);
            $object->setRelationId($rID);
            $rIDimg = Media::addElement($elementDocPart, 'image', $icon, new Image($icon));
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
        $this->checkValidity('footnote');

        $footnote = new FootnoteElement($paragraphStyle);
        $refID = FootnoteCollection::addFootnoteElement($footnote);
        $footnote->setDocPart('footnote', $this->getDocPartId());
        $footnote->setRelationId($refID);
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
     */
    public function addCheckBox($name, $text, $fontStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('checkbox');

        $checkBox = new CheckBox(String::toUTF8($name), String::toUTF8($text), $fontStyle, $paragraphStyle);
        $checkBox->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->elements[] = $checkBox;

        return $checkBox;
    }

    /**
     * Get section number
     *
     * @return integer
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Set doc part
     *
     * @param string $docPart
     * @param integer $docPartId
     */
    public function setDocPart($docPart, $docPartId = 1)
    {
        $this->docPart = $docPart;
        $this->docPartId = $docPartId;
    }

    /**
     * Get doc part
     *
     * @return string
     */
    public function getDocPart()
    {
        return $this->docPart;
    }

    /**
     * Get doc part Id
     *
     * @return integer
     */
    public function getDocPartId()
    {
        return $this->docPartId;
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
        $this->checkValidity('relationid');
        return $this->relationId;
    }

    /**
     * Set relation Id
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->checkValidity('relationid');
        $this->relationId = $rId;
    }

    /**
     * Check if element is located in section doc part (as opposed to header/footer)
     *
     * @return boolean
     */
    public function isInSection()
    {
        return ($this->docPart == 'section');
    }

    /**
     * Set style value
     *
     * @param mixed $styleObject Style object
     * @param mixed $styleValue Style value
     * @param boolean $returnObject Always return object
     */
    protected function setStyle($styleObject, $styleValue = null, $returnObject = false)
    {
        if (!is_null($styleValue) && is_array($styleValue)) {
            foreach ($styleValue as $key => $value) {
                if (substr($key, 0, 1) == '_') {
                    $key = substr($key, 1);
                }
                $styleObject->setStyleValue($key, $value);
            }
            $style = $styleObject;
        } else {
            $style = $returnObject ? $styleObject : $styleValue;
        }

        return $style;
    }

    /**
     * Check if a method is allowed for the current container
     *
     * @param string $method
     * @return boolean
     */
    private function checkValidity($method)
    {
        // Valid containers for each element
        $allContainers = array('section', 'header', 'footer', 'cell', 'textrun', 'footnote');
        $validContainers = array(
            'text'          => $allContainers,
            'link'          => $allContainers,
            'textbreak'     => $allContainers,
            'image'         => $allContainers,
            'object'        => $allContainers,
            'textrun'       => array('section', 'header', 'footer', 'cell'),
            'listitem'      => array('section', 'header', 'footer', 'cell'),
            'checkbox'      => array('section', 'header', 'footer', 'cell'),
            'table'         => array('section', 'header', 'footer'),
            'footnote'      => array('section', 'textrun', 'cell'),
            'preservetext'  => array('header', 'footer', 'cell'),
            'title'         => array('section'),
        );
        // Special condition, e.g. preservetext can only exists in cell when
        // the cell is located in header or footer
        $validContainerInContainers = array(
            'preservetext'  => array(array('cell'), array('header', 'footer')),
            'footnote'      => array(array('cell', 'textrun'), array('section')),
        );

        // Check if a method is valid for current container
        if (array_key_exists($method, $validContainers)) {
            if (!in_array($this->container, $validContainers[$method])) {
                throw new \BadMethodCallException();
            }
        }
        // Check if a method is valid for current container, located in other container
        if (array_key_exists($method, $validContainerInContainers)) {
            $rules = $validContainerInContainers[$method];
            $containers = $rules[0];
            $allowedDocParts = $rules[1];
            foreach ($containers as $container) {
                if ($this->container == $container && !in_array($this->getDocPart(), $allowedDocParts)) {
                    throw new \BadMethodCallException();
                }
            }
        }

        return true;
    }

    /**
     * Return element location in document: section, headerx, or footerx
     */
    private function checkElementDocPart()
    {
        $isCellTextrun = in_array($this->container, array('cell', 'textrun'));
        $docPart = $isCellTextrun ? $this->getDocPart() : $this->container;
        $docPartId = $isCellTextrun ? $this->getDocPartId() : $this->sectionId;
        $inHeaderFooter = ($docPart == 'header' || $docPart == 'footer');

        return $inHeaderFooter ? $docPart . $docPartId : $docPart;
    }

    /**
     * Add memory image element
     *
     * @param string $src
     * @param mixed $style
     * @deprecated 0.9.0
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     */
    public function createFootnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle);
    }
}
