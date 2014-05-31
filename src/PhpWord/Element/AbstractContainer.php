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
     * Container type Section|Header|Footer|Footnote|Endnote|Cell|TextRun|TextBox|ListItemRun
     *
     * @var string
     */
    protected $container;

    /**
     * Add element
     *
     * Each element has different number of parameters passed
     *
     * @param string $elementName
     * @return \PhpOffice\PhpWord\Element\AbstractElement
     */
    protected function addElement($elementName)
    {
        $elementClass = __NAMESPACE__ . '\\' . $elementName;
        $this->checkValidity($elementName);

        // Get arguments
        $args = func_get_args();
        $withoutP = in_array($this->container, array('TextRun', 'Footnote', 'Endnote', 'ListItemRun', 'Field'));
        if ($withoutP && ($elementName == 'Text' || $elementName == 'PreserveText')) {
            $args[3] = null; // Remove paragraph style for texts in textrun
        }
        $source = '';
        if (count($args) > 1) {
            $source = $args[1];
        }

        // Create element using reflection
        $reflection = new \ReflectionClass($elementClass);
        $elementArgs = $args;
        array_shift($elementArgs); // Shift the $elementName off the beginning of array

        /** @var \PhpOffice\PhpWord\Element\AbstractElement $element Type hint */
        $element = $reflection->newInstanceArgs($elementArgs);

        // Set nested level and relation Id
        $this->setElementNestedLevel($element);
        $this->setElementRelationId($element, $elementName, $source);

        // Set other properties and add element into collection
        $element->setDocPart($this->getDocPart(), $this->getDocPartId());
        $element->setElementIndex($this->countElements() + 1);
        $element->setElementId();
        $element->setPhpWord($this->phpWord);

        $this->elements[] = $element;

        return $element;
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
     * @return int
     */
    public function countElements()
    {
        return count($this->elements);
    }

    /**
     * Set element nested level based on container; add one when it's inside a cell
     */
    private function setElementNestedLevel(AbstractElement $element)
    {
        if ($this->container == 'Cell') {
            $element->setNestedLevel($this->getNestedLevel() + 1);
        } else {
            $element->setNestedLevel($this->getNestedLevel());
        }
    }

    /**
     * Set relation Id
     *
     * @param string $elementName
     * @param string $source
     */
    private function setElementRelationId(AbstractElement $element, $elementName, $source)
    {
        $mediaContainer = $this->getMediaContainer();
        $hasMediaRelation = in_array($elementName, array('Link', 'Image', 'Object'));
        $hasOtherRelation = in_array($elementName, array('Footnote', 'Endnote', 'Title'));

        // Set relation Id for media elements (link, image, object; legacy of OOXML)
        // Only Image that needs to be passed to Media class
        if ($hasMediaRelation) {
            /** @var \PhpOffice\PhpWord\Element\Image $element Type hint */
            $image = ($elementName == 'Image') ? $element : null;
            $rId = Media::addElement($mediaContainer, strtolower($elementName), $source, $image);
            $element->setRelationId($rId);
        }

        // Set relation Id for icon of object element
        if ($elementName == 'Object') {
            /** @var \PhpOffice\PhpWord\Element\Object $element Type hint */
            $rIdIcon = Media::addElement($mediaContainer, 'image', $element->getIcon(), new Image($element->getIcon()));
            $element->setImageRelationId($rIdIcon);
        }

        // Set relation Id for elements that will be registered in the Collection subnamespaces
        if ($hasOtherRelation && $this->phpWord instanceof PhpWord) {
            $addMethod = "add{$elementName}";
            $rId = $this->phpWord->$addMethod($element);
            $element->setRelationId($rId);
        }
    }

    /**
     * Add text/preservetext element
     *
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\Text|\PhpOffice\PhpWord\Element\PreserveText
     */
    public function addText($text, $fontStyle = null, $paragraphStyle = null)
    {
        return $this->addElement('Text', $text, $fontStyle, $paragraphStyle);
    }

    /**
     * Add textrun element
     *
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\TextRun
     */
    public function addTextRun($paragraphStyle = null)
    {
        return $this->addElement('TextRun', $paragraphStyle);
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
        return $this->addElement('Link', $target, $text, $fontStyle, $paragraphStyle);
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
        return $this->addElement('PreserveText', $text, $fontStyle, $paragraphStyle);
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
            $this->addElement('TextBreak', $fontStyle, $paragraphStyle);
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
        return $this->addElement('ListItem', $text, $depth, $fontStyle, $listStyle, $paragraphStyle);
    }

    /**
     * Add listitemrun element
     *
     * @param int $depth
     * @param mixed $listStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\ListItemRun
     */
    public function addListItemRun($depth = 0, $listStyle = null, $paragraphStyle = null)
    {
        return $this->addElement('ListItemRun', $depth, $listStyle, $paragraphStyle);
    }

    /**
     * Add table element
     *
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Table
     */
    public function addTable($style = null)
    {
        return $this->addElement('Table', $style);
    }

    /**
     * Add image element
     *
     * @param string $source
     * @param mixed $style Image style
     * @param bool $isWatermark
     * @return \PhpOffice\PhpWord\Element\Image
     */
    public function addImage($source, $style = null, $isWatermark = false)
    {
        return $this->addElement('Image', $source, $style, $isWatermark);
    }

    /**
     * Add OLE-object element
     *
     * All exceptions should be handled by \PhpOffice\PhpWord\Element\Object
     *
     * @param string $source
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Object
     */
    public function addObject($source, $style = null)
    {
        return $this->addElement('Object', $source, $style);
    }

    /**
     * Add footnote element
     *
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\Footnote
     */
    public function addFootnote($paragraphStyle = null)
    {
        return $this->addElement('Footnote', $paragraphStyle);
    }

    /**
     * Add endnote element
     *
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\Endnote
     */
    public function addEndnote($paragraphStyle = null)
    {
        return $this->addElement('Endnote', $paragraphStyle);
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
        return $this->addElement('CheckBox', $name, $text, $fontStyle, $paragraphStyle);
    }

    /**
     * Add textbox element
     *
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\TextBox
     */
    public function addTextBox($style = null)
    {
        return $this->addElement('TextBox', $style);
    }

    /**
     * Add field element
     *
     * @param string $type
     * @param array $properties
     * @param array $options
     * @return \PhpOffice\PhpWord\Element\Field
     */
    public function addField($type = null, $properties = array(), $options = array())
    {
        return $this->addElement('Field', $type, $properties, $options);
    }

    /**
     * Add line element
     *
     * @param mixed $lineStyle
     * @return \PhpOffice\PhpWord\Element\Line
     */
    public function addLine($lineStyle = null)
    {
        return $this->addElement('Line', $lineStyle);

    }

    /**
     * Check if a method is allowed for the current container
     *
     * @param string $method
     * @return bool
     * @throws \BadMethodCallException
     */
    private function checkValidity($method)
    {
        // Valid containers for each element
        $allContainers = array(
            'Section', 'Header', 'Footer', 'Footnote', 'Endnote',
            'Cell', 'TextRun', 'TextBox', 'ListItemRun',
        );
        $validContainers = array(
            'Text'          => $allContainers,
            'Link'          => $allContainers,
            'TextBreak'     => $allContainers,
            'Image'         => $allContainers,
            'Object'        => $allContainers,
            'Field'         => $allContainers,
            'Line'          => $allContainers,
            'TextRun'       => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'ListItem'      => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'ListItemRun'   => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'Table'         => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'CheckBox'      => array('Section', 'Header', 'Footer', 'Cell'),
            'TextBox'       => array('Section', 'Header', 'Footer', 'Cell'),
            'Footnote'      => array('Section', 'TextRun', 'Cell'),
            'Endnote'       => array('Section', 'TextRun', 'Cell'),
            'PreserveText'  => array('Header', 'Footer', 'Cell'),
        );
        // Special condition, e.g. preservetext can only exists in cell when
        // the cell is located in header or footer
        $validSubcontainers = array(
            'PreserveText'  => array(array('Cell'), array('Header', 'Footer')),
            'Footnote'      => array(array('Cell', 'TextRun'), array('Section')),
            'Endnote'       => array(array('Cell', 'TextRun'), array('Section')),
        );

        // Check if a method is valid for current container
        if (array_key_exists($method, $validContainers)) {
            if (!in_array($this->container, $validContainers[$method])) {
                throw new \BadMethodCallException("Cannot add $method in $this->container.");
            }
        }
        // Check if a method is valid for current container, located in other container
        if (array_key_exists($method, $validSubcontainers)) {
            $rules = $validSubcontainers[$method];
            $containers = $rules[0];
            $allowedDocParts = $rules[1];
            foreach ($containers as $container) {
                if ($this->container == $container && !in_array($this->getDocPart(), $allowedDocParts)) {
                    throw new \BadMethodCallException("Cannot add $method in $this->container.");
                }
            }
        }

        return true;
    }

    /**
     * Return media element (image, object, link) container name
     *
     * @return string section|headerx|footerx|footnote|endnote
     */
    private function getMediaContainer()
    {
        $partName = $this->container;
        if (in_array($partName, array('Cell', 'TextRun', 'TextBox', 'ListItemRun'))) {
            $partName = $this->getDocPart();
        }
        if ($partName == 'Header' || $partName == 'Footer') {
            $partName .= $this->getDocPartId();
        }

        return strtolower($partName);
    }

    /**
     * Add memory image element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Image
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
     * @return \PhpOffice\PhpWord\Element\TextRun
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
     * @return \PhpOffice\PhpWord\Element\Footnote
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function createFootnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle);
    }
}
