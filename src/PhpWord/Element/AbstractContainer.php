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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Container abstract class
 *
 * @method Text addText(string $text, mixed $fStyle = null, mixed $pStyle = null)
 * @method TextRun addTextRun(mixed $pStyle = null)
 * @method Bookmark addBookmark(string $name)
 * @method Link addLink(string $target, string $text = null, mixed $fStyle = null, mixed $pStyle = null, boolean $internal = false)
 * @method PreserveText addPreserveText(string $text, mixed $fStyle = null, mixed $pStyle = null)
 * @method void addTextBreak(int $count = 1, mixed $fStyle = null, mixed $pStyle = null)
 * @method ListItem addListItem(string $txt, int $depth = 0, mixed $font = null, mixed $list = null, mixed $para = null)
 * @method ListItemRun addListItemRun(int $depth = 0, mixed $listStyle = null, mixed $pStyle = null)
 * @method Footnote addFootnote(mixed $pStyle = null)
 * @method Endnote addEndnote(mixed $pStyle = null)
 * @method CheckBox addCheckBox(string $name, $text, mixed $fStyle = null, mixed $pStyle = null)
 * @method Title addTitle(string $text, int $depth = 1)
 * @method TOC addTOC(mixed $fontStyle = null, mixed $tocStyle = null, int $minDepth = 1, int $maxDepth = 9)
 * @method PageBreak addPageBreak()
 * @method Table addTable(mixed $style = null)
 * @method Image addImage(string $source, mixed $style = null, bool $isWatermark = false, $name = null)
 * @method OLEObject addOLEObject(string $source, mixed $style = null)
 * @method TextBox addTextBox(mixed $style = null)
 * @method Field addField(string $type = null, array $properties = array(), array $options = array(), mixed $text = null)
 * @method Line addLine(mixed $lineStyle = null)
 * @method Shape addShape(string $type, mixed $style = null)
 * @method Chart addChart(string $type, array $categories, array $values, array $style = null)
 * @method FormField addFormField(string $type, mixed $fStyle = null, mixed $pStyle = null)
 * @method SDT addSDT(string $type)
 *
 * @method \PhpOffice\PhpWord\Element\OLEObject addObject(string $source, mixed $style = null) deprecated, use addOLEObject instead
 *
 * @since 0.10.0
 */
abstract class AbstractContainer extends AbstractElement
{
    /**
     * Elements collection
     *
     * @var \PhpOffice\PhpWord\Element\AbstractElement[]
     */
    protected $elements = array();

    /**
     * Container type Section|Header|Footer|Footnote|Endnote|Cell|TextRun|TextBox|ListItemRun|TrackChange
     *
     * @var string
     */
    protected $container;

    /**
     * Magic method to catch all 'addElement' variation
     *
     * This removes addText, addTextRun, etc. When adding new element, we have to
     * add the model in the class docblock with `@method`.
     *
     * Warning: This makes capitalization matters, e.g. addCheckbox or addcheckbox won't work.
     *
     * @param mixed $function
     * @param mixed $args
     * @return \PhpOffice\PhpWord\Element\AbstractElement
     */
    public function __call($function, $args)
    {
        $elements = array(
            'Text', 'TextRun', 'Bookmark', 'Link', 'PreserveText', 'TextBreak',
            'ListItem', 'ListItemRun', 'Table', 'Image', 'Object', 'OLEObject',
            'Footnote', 'Endnote', 'CheckBox', 'TextBox', 'Field',
            'Line', 'Shape', 'Title', 'TOC', 'PageBreak',
            'Chart', 'FormField', 'SDT', 'Comment',
        );
        $functions = array();
        foreach ($elements as $element) {
            $functions['add' . strtolower($element)] = $element == 'Object' ? 'OLEObject' : $element;
        }

        // Run valid `add` command
        $function = strtolower($function);
        if (isset($functions[$function])) {
            $element = $functions[$function];

            // Special case for TextBreak
            // @todo Remove the `$count` parameter in 1.0.0 to make this element similiar to other elements?
            if ($element == 'TextBreak') {
                list($count, $fontStyle, $paragraphStyle) = array_pad($args, 3, null);
                if ($count === null) {
                    $count = 1;
                }
                for ($i = 1; $i <= $count; $i++) {
                    $this->addElement($element, $fontStyle, $paragraphStyle);
                }
            } else {
                // All other elements
                array_unshift($args, $element); // Prepend element name to the beginning of args array
                return call_user_func_array(array($this, 'addElement'), $args);
            }
        }

        return null;
    }

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

        // Create element using reflection
        $reflection = new \ReflectionClass($elementClass);
        $elementArgs = $args;
        array_shift($elementArgs); // Shift the $elementName off the beginning of array

        /** @var \PhpOffice\PhpWord\Element\AbstractElement $element Type hint */
        $element = $reflection->newInstanceArgs($elementArgs);

        // Set parent container
        $element->setParentContainer($this);
        $element->setElementIndex($this->countElements() + 1);
        $element->setElementId();

        $this->elements[] = $element;

        return $element;
    }

    /**
     * Get all elements
     *
     * @return \PhpOffice\PhpWord\Element\AbstractElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Returns the element at the requested position
     *
     * @param int $index
     * @return \PhpOffice\PhpWord\Element\AbstractElement|null
     */
    public function getElement($index)
    {
        if (array_key_exists($index, $this->elements)) {
            return $this->elements[$index];
        }

        return null;
    }

    /**
     * Removes the element at requested index
     *
     * @param int|\PhpOffice\PhpWord\Element\AbstractElement $toRemove
     */
    public function removeElement($toRemove)
    {
        if (is_int($toRemove) && array_key_exists($toRemove, $this->elements)) {
            unset($this->elements[$toRemove]);
        } elseif ($toRemove instanceof \PhpOffice\PhpWord\Element\AbstractElement) {
            foreach ($this->elements as $key => $element) {
                if ($element->getElementId() === $toRemove->getElementId()) {
                    unset($this->elements[$key]);

                    return;
                }
            }
        }
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
     * Check if a method is allowed for the current container
     *
     * @param string $method
     *
     * @throws \BadMethodCallException
     * @return bool
     */
    private function checkValidity($method)
    {
        $generalContainers = array(
            'Section', 'Header', 'Footer', 'Footnote', 'Endnote', 'Cell', 'TextRun', 'TextBox', 'ListItemRun', 'TrackChange',
        );

        $validContainers = array(
            'Text'          => $generalContainers,
            'Bookmark'      => $generalContainers,
            'Link'          => $generalContainers,
            'TextBreak'     => $generalContainers,
            'Image'         => $generalContainers,
            'OLEObject'     => $generalContainers,
            'Field'         => $generalContainers,
            'Line'          => $generalContainers,
            'Shape'         => $generalContainers,
            'FormField'     => $generalContainers,
            'SDT'           => $generalContainers,
            'TrackChange'   => $generalContainers,
            'TextRun'       => array('Section', 'Header', 'Footer', 'Cell', 'TextBox', 'TrackChange', 'ListItemRun'),
            'ListItem'      => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'ListItemRun'   => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'Table'         => array('Section', 'Header', 'Footer', 'Cell', 'TextBox'),
            'CheckBox'      => array('Section', 'Header', 'Footer', 'Cell', 'TextRun'),
            'TextBox'       => array('Section', 'Header', 'Footer', 'Cell'),
            'Footnote'      => array('Section', 'TextRun', 'Cell', 'ListItemRun'),
            'Endnote'       => array('Section', 'TextRun', 'Cell'),
            'PreserveText'  => array('Section', 'Header', 'Footer', 'Cell'),
            'Title'         => array('Section', 'Cell'),
            'TOC'           => array('Section'),
            'PageBreak'     => array('Section'),
            'Chart'         => array('Section', 'Cell'),
        );

        // Special condition, e.g. preservetext can only exists in cell when
        // the cell is located in header or footer
        $validSubcontainers = array(
            'PreserveText'  => array(array('Cell'), array('Header', 'Footer')),
            'Footnote'      => array(array('Cell', 'TextRun'), array('Section')),
            'Endnote'       => array(array('Cell', 'TextRun'), array('Section')),
        );

        // Check if a method is valid for current container
        if (isset($validContainers[$method])) {
            if (!in_array($this->container, $validContainers[$method])) {
                throw new \BadMethodCallException("Cannot add {$method} in {$this->container}.");
            }
        }

        // Check if a method is valid for current container, located in other container
        if (isset($validSubcontainers[$method])) {
            $rules = $validSubcontainers[$method];
            $containers = $rules[0];
            $allowedDocParts = $rules[1];
            foreach ($containers as $container) {
                if ($this->container == $container && !in_array($this->getDocPart(), $allowedDocParts)) {
                    throw new \BadMethodCallException("Cannot add {$method} in {$this->container}.");
                }
            }
        }

        return true;
    }

    /**
     * Create textrun element
     *
     * @deprecated 0.10.0
     *
     * @param mixed $paragraphStyle
     *
     * @return \PhpOffice\PhpWord\Element\TextRun
     *
     * @codeCoverageIgnore
     */
    public function createTextRun($paragraphStyle = null)
    {
        return $this->addTextRun($paragraphStyle);
    }

    /**
     * Create footnote element
     *
     * @deprecated 0.10.0
     *
     * @param mixed $paragraphStyle
     *
     * @return \PhpOffice\PhpWord\Element\Footnote
     *
     * @codeCoverageIgnore
     */
    public function createFootnote($paragraphStyle = null)
    {
        return $this->addFootnote($paragraphStyle);
    }
}
