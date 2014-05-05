<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;

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
     * Add text/preservetext element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @param string $elementName Text|PreserveText
     * @return \PhpOffice\PhpWord\Element\Text|\PhpOffice\PhpWord\Element\PreserveText
     */
    public function addText($text, $fontStyle = null, $paragraphStyle = null, $elementName = 'Text')
    {
        $this->checkValidity($elementName);
        $elementClass = 'PhpOffice\\PhpWord\\Element\\' . $elementName;

        // Reset paragraph style for footnote and textrun. They have their own
        if (in_array($this->container, array('textrun', 'footnote', 'endnote'))) {
            $paragraphStyle = null;
        }

        $element = new $elementClass($text, $fontStyle, $paragraphStyle);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($element);

        return $element;
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

        $element = new TextRun($paragraphStyle);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($element);

        return $element;
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

        $element = new Link($target, $text, $fontStyle, $paragraphStyle);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());

        $rId = Media::addElement($elementDocPart, 'link', $target);
        $element->setRelationId($rId);

        $this->addElement($element);

        return $element;
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
        return $this->addText($text, $fontStyle, $paragraphStyle, 'PreserveText');
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
            $element = new TextBreak($fontStyle, $paragraphStyle);
            $element->setDocPart($this->getDocPart(), $this->getDocPartId());
            $this->addElement($element);
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

        $element = new ListItem($text, $depth, $fontStyle, $listStyle, $paragraphStyle);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($element);

        return $element;
    }

    /**
     * Add image element
     *
     * @param string $source
     * @param mixed $style Image style
     * @param boolean $isWatermark
     * @return \PhpOffice\PhpWord\Element\Image
     */
    public function addImage($source, $style = null, $isWatermark = false)
    {
        $this->checkValidity('Image');
        $elementDocPart = $this->checkElementDocPart();

        $element = new Image($source, $style, $isWatermark);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());

        $rId = Media::addElement($elementDocPart, 'image', $source, $element);
        $element->setRelationId($rId);

        $this->addElement($element);

        return $element;
    }

    /**
     * Add OLE-object element
     *
     * All exceptions should be handled by \PhpOffice\PhpWord\Element\Object
     *
     * @param string $source
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Object
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function addObject($source, $style = null)
    {
        $this->checkValidity('Object');
        $elementDocPart = $this->checkElementDocPart();

        $element = new Object($source, $style);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());

        $rId = Media::addElement($elementDocPart, 'object', $source);
        $element->setRelationId($rId);
        $rIdIcon = Media::addElement($elementDocPart, 'image', $element->getIcon(), new Image($element->getIcon()));
        $element->setImageRelationId($rIdIcon);

        $this->addElement($element);

        return $element;
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
        $elementClass = 'PhpOffice\\PhpWord\\Element\\' . $elementName;
        $docPart = strtolower($elementName);
        $addMethod = "add{$elementName}";

        $element = new $elementClass($paragraphStyle);
        if ($this->phpWord instanceof PhpWord) {
            $rId = $this->phpWord->$addMethod($element);
        }
        $element->setDocPart($docPart, $this->getDocPartId());
        $element->setRelationId($rId);
        $this->addElement($element);

        return $element;
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

        $element = new CheckBox($name, $text, $fontStyle, $paragraphStyle);
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());
        $this->addElement($element);

        return $element;
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
        $docPartId = $inHeaderFooter ? $this->getDocPartId() : $docPartId;
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
