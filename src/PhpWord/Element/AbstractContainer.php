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
        $argsCount = func_num_args();
        $withoutP = in_array($this->container, array('TextRun', 'Footnote', 'Endnote', 'ListItemRun'));
        if ($withoutP && ($elementName == 'Text' || $elementName == 'PreserveText')) {
            $args[3] = null;
        }

        // Create element
        if ($argsCount == 2) {       // TextRun, TextBox, Table, Footnote, Endnote
            $element = new $elementClass($args[1]);
        } elseif ($argsCount == 3) { // Object, TextBreak, Title
            $element = new $elementClass($args[1], $args[2]);
        } elseif ($argsCount == 4) { // PreserveText, Text, Image
            $element = new $elementClass($args[1], $args[2], $args[3]);
        } elseif ($argsCount == 5) { // CheckBox, Link, ListItemRun, TOC
            $element = new $elementClass($args[1], $args[2], $args[3], $args[4]);
        } elseif ($argsCount == 6) { // ListItem
            $element = new $elementClass($args[1], $args[2], $args[3], $args[4], $args[5]);
        } else {                     // Page Break
            $element = new $elementClass();
        }

        // Set relation Id for media collection
        $mediaContainer = $this->getMediaContainer();
        if (in_array($elementName, array('Link', 'Image', 'Object'))) {
            if ($elementName == 'Image') {
                $rId = Media::addElement($mediaContainer, strtolower($elementName), $args[1], $element);
            } else {
                $rId = Media::addElement($mediaContainer, strtolower($elementName), $args[1]);
            }
            $element->setRelationId($rId);
        }
        if ($elementName == 'Object') {
            $rIdIcon = Media::addElement($mediaContainer, 'image', $element->getIcon(), new Image($element->getIcon()));
            $element->setImageRelationId($rIdIcon);
        }

        // Set relation Id for other collection
        if (in_array($elementName, array('Footnote', 'Endnote', 'Title')) && $this->phpWord instanceof PhpWord) {
            $addMethod = "add{$elementName}";
            $rId = $this->phpWord->$addMethod($element);
            $element->setRelationId($rId);
        }

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
     * @param mixed $fontStyle
     * @param mixed $listStyle
     * @param mixed $paragraphStyle
     * @return \PhpOffice\PhpWord\Element\ListItemRun
     */
    public function addListItemRun($depth = 0, $fontStyle = null, $listStyle = null, $paragraphStyle = null)
    {
        return $this->addElement('ListItemRun', $depth, $fontStyle, $listStyle, $paragraphStyle);
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
     * Check if a method is allowed for the current container
     *
     * @param string $method
     * @return bool
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
