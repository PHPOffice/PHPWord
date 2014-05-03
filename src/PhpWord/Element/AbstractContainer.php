<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Element\CheckBox;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Object;
use PhpOffice\PhpWord\Element\PreserveText;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Exception\InvalidObjectException;

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
        $type = get_class($element);
        $type = str_replace('PhpOffice\\PhpWord\\Element\\', '', $type);
        $element->setElementIndex($this->countElements() + 1);
        $element->setElementId();
        $element->setPhpWord($this->phpWord);
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
     * @return \PhpOffice\PhpWord\Element\Text
     */
    public function addText($text, $fontStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('Text');

        // Reset paragraph style for footnote and textrun. They have their own
        if (in_array($this->container, array('textrun', 'footnote', 'endnote'))) {
            $paragraphStyle = null;
        }

        $textObject = new Text($text, $fontStyle, $paragraphStyle);
        $textObject->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($textObject);

        return $textObject;
    }

    /**
     * Add textrun element
     *
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\TextRun
     */
    public function addTextRun($paragraphStyle = null)
    {
        $this->checkValidity('Textrun');

        $textRun = new TextRun($paragraphStyle);
        $textRun->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($textRun);

        return $textRun;
    }

    /**
     * Add link element
     *
     * @param string $target
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\Link
     */
    public function addLink($target, $text = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('Link');
        $elementDocPart = $this->checkElementDocPart();

        $link = new Link($target, $text, $fontStyle, $paragraphStyle);
        $link->setDocPart($this->getDocPart(), $this->getDocPartId());
        $rId = Media::addElement($elementDocPart, 'link', $target);
        $link->setRelationId($rId);
        $this->addElement($link);

        return $link;
    }

    /**
     * Add preserve text element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\PreserveText
     */
    public function addPreserveText($text, $fontStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('PreserveText');

        $preserveText = new PreserveText($text, $fontStyle, $paragraphStyle);
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
        $this->checkValidity('TextBreak');

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
     * @param mixed $listStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\ListItem
     */
    public function addListItem($text, $depth = 0, $fontStyle = null, $listStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('ListItem');

        $listItem = new ListItem($text, $depth, $fontStyle, $listStyle, $paragraphStyle);
        $listItem->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($listItem);

        return $listItem;
    }

    /**
     * Add image element
     *
     * @param string $src
     * @param mixed $style Image style
     * @param boolean $isWatermark
     * @return \PhpOffice\PhpWord\Element\Image
     */
    public function addImage($src, $style = null, $isWatermark = false)
    {
        $this->checkValidity('Image');
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
     * @return \PhpOffice\PhpWord\Element\Object
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function addObject($src, $style = null)
    {
        $this->checkValidity('Object');
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
     * @param string $elementName
     * @return \PhpOffice\PhpWord\Element\Footnote
     */
    public function addFootnote($paragraphStyle = null, $elementName = 'Footnote')
    {
        $this->checkValidity($elementName);
        $docPart = strtolower($elementName);
        $addMethod = "add{$elementName}";
        $elementClass = 'PhpOffice\\PhpWord\\Element\\' . $elementName;

        $note = new $elementClass($paragraphStyle);
        // if ($this->phpWord instanceof PhpWord) {
            $rId = $this->phpWord->$addMethod($note);
        // }
        $note->setDocPart($docPart, $this->getDocPartId());
        $note->setRelationId($rId);
        $this->addElement($note);

        return $note;
    }

    /**
     * Add endnote element
     *
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\Endnote
     */
    public function addEndnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle, 'Endnote');
    }

    /**
     * Add a CheckBox Element
     *
     * @param string $name
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\CheckBox
     */
    public function addCheckBox($name, $text, $fontStyle = null, $paragraphStyle = null)
    {
        $this->checkValidity('CheckBox');

        $checkBox = new CheckBox($name, $text, $fontStyle, $paragraphStyle);
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
            'Text'          => $allContainers,
            'Link'          => $allContainers,
            'TextBreak'     => $allContainers,
            'Image'         => $allContainers,
            'Object'        => $allContainers,
            'TextRun'       => array('section', 'header', 'footer', 'cell'),
            'ListItem'      => array('section', 'header', 'footer', 'cell'),
            'CheckBox'      => array('section', 'header', 'footer', 'cell'),
            'Footnote'      => array('section', 'textrun', 'cell'),
            'Endnote'       => array('section', 'textrun', 'cell'),
            'PreserveText'  => array('header', 'footer', 'cell'),
        );
        // Special condition, e.g. preservetext can only exists in cell when
        // the cell is located in header or footer
        $validSubcontainers = array(
            'PreserveText'  => array(array('cell'), array('header', 'footer')),
            'Footnote'      => array(array('cell', 'textrun'), array('section')),
            'Endnote'       => array(array('cell', 'textrun'), array('section')),
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
