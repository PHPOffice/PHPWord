<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Endnotes;
use PhpOffice\PhpWord\Footnotes;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\TOC as Titles;
use PhpOffice\PhpWord\Exception\InvalidObjectException;
use PhpOffice\PhpWord\Shared\String;

/**
 * Container abstract class
 *
 * @since 0.10.0
 */
abstract class AbstractContainer extends AbstractElement
{
    /**
     * Elements collection
     *
     * @var array
     */
    protected $elements = array();

    /**
     * Set element index and unique id, and add element into elements collection
     */
    protected function addElement(AbstractElement $element)
    {
        $element->setElementIndex($this->countElements() + 1);
        $element->setElementId();
        $this->elements[] = $element;
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
     * Count elements
     *
     * @return integer
     */
    public function countElements()
    {
        return count($this->elements);
    }

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
        if (in_array($this->container, array('textrun', 'footnote', 'endnote'))) {
            $paragraphStyle = null;
        }

        $text = String::toUTF8($text);
        $textObject = new Text($text, $fontStyle, $paragraphStyle);
        $textObject->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($textObject);

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
        $this->addElement($textRun);

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
        $rId = Media::addElement($elementDocPart, 'link', $linkSrc);
        $link->setRelationId($rId);
        $this->addElement($link);

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
        $data = Titles::addTitle($text, $depth);
        $anchor = $data[0];
        $bookmarkId = $data[1];
        $title->setAnchor($anchor);
        $title->setBookmarkId($bookmarkId);
        $this->addElement($title);

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
        $this->addElement($preserveText);

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
            $this->addElement($textBreak);
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
        $this->addElement($listItem);

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
        $this->addElement($table);

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
        $rId = Media::addElement($elementDocPart, 'image', $src, $image);
        $image->setRelationId($rId);
        $this->addElement($image);

        return $image;
    }

    /**
     * Add OLE-object element
     *
     * All exceptions should be handled by \PhpOffice\PhpWord\Element\Object
     *
     * @param string $src
     * @param mixed $style
     * @return Object
     * @throws \PhpOffice\PhpWord\Exception\Exception
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
            $icon = realpath(__DIR__ . "/../resources/{$ext}.png");
            $rId = Media::addElement($elementDocPart, 'object', $src);
            $object->setRelationId($rId);
            $rIdimg = Media::addElement($elementDocPart, 'image', $icon, new Image($icon));
            $object->setImageRelationId($rIdimg);
            $this->addElement($object);

            return $object;
        } else {
            throw new InvalidObjectException();
        }
    }

    /**
     * Add footnote element
     *
     * @param mixed $paragraphStyle
     * @return Footnote
     */
    public function addFootnote($paragraphStyle = null)
    {
        $this->checkValidity('footnote');

        $footnote = new Footnote($paragraphStyle);
        $rId = Footnotes::addElement($footnote);

        $footnote->setDocPart('footnote', $this->getDocPartId());
        $footnote->setRelationId($rId);
        $this->addElement($footnote);

        return $footnote;
    }

    /**
     * Add endnote element
     *
     * @param mixed $paragraphStyle
     * @return Endnote
     */
    public function addEndnote($paragraphStyle = null)
    {
        $this->checkValidity('endnote');

        $endnote = new Endnote($paragraphStyle);
        $rId = Endnotes::addElement($endnote);

        $endnote->setDocPart('endnote', $this->getDocPartId());
        $endnote->setRelationId($rId);
        $this->addElement($endnote);

        return $endnote;
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
        $this->addElement($checkBox);

        return $checkBox;
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
        $allContainers = array('section', 'header', 'footer', 'cell', 'textrun', 'footnote', 'endnote');
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
            'endnote'       => array('section', 'textrun', 'cell'),
            'preservetext'  => array('header', 'footer', 'cell'),
            'title'         => array('section'),
        );
        // Special condition, e.g. preservetext can only exists in cell when
        // the cell is located in header or footer
        $validSubcontainers = array(
            'preservetext'  => array(array('cell'), array('header', 'footer')),
            'footnote'      => array(array('cell', 'textrun'), array('section')),
            'endnote'       => array(array('cell', 'textrun'), array('section')),
        );

        // Check if a method is valid for current container
        if (array_key_exists($method, $validContainers)) {
            if (!in_array($this->container, $validContainers[$method])) {
                throw new \BadMethodCallException();
            }
        }
        // Check if a method is valid for current container, located in other container
        if (array_key_exists($method, $validSubcontainers)) {
            $rules = $validSubcontainers[$method];
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
     * @deprecated 0.10.0
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
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function createFootnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle);
    }
}
