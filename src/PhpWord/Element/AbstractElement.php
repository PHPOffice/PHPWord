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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;

/**
 * Element abstract class
 *
 * @since 0.10.0
 */
abstract class AbstractElement
{
    /**
     * PhpWord object
     *
     * @var \PhpOffice\PhpWord\PhpWord
     */
    protected $phpWord;

    /**
     * Section Id
     *
     * @var int
     */
    protected $sectionId;

    /**
     * Document part type: Section|Header|Footer|Footnote|Endnote
     *
     * Used by textrun and cell container to determine where the element is
     * located because it will affect the availability of other element,
     * e.g. footnote will not be available when $docPart is header or footer.
     *
     * @var string
     */
    protected $docPart = 'Section';

    /**
     * Document part Id
     *
     * For header and footer, this will be = ($sectionId - 1) * 3 + $index
     * because the max number of header/footer in every page is 3, i.e.
     * AUTO, FIRST, and EVEN (AUTO = ODD)
     *
     * @var int
     */
    protected $docPartId = 1;

    /**
     * Index of element in the elements collection (start with 1)
     *
     * @var int
     */
    protected $elementIndex = 1;

    /**
     * Unique Id for element
     *
     * @var int
     */
    protected $elementId;

    /**
     * Relation Id
     *
     * @var int
     */
    protected $relationId;

    /**
     * Depth of table container nested level; Primarily used for RTF writer/reader
     *
     * 0 = Not in a table; 1 = in a table; 2 = in a table inside another table, etc.
     *
     * @var int
     */
    private $nestedLevel = 0;

    /**
     * Parent container type
     *
     * @var string
     */
    private $parentContainer;

    /**
     * Has media relation flag; true for Link, Image, and Object
     *
     * @var bool
     */
    protected $mediaRelation = false;

    /**
     * Is part of collection; true for Title, Footnote, Endnote, and Chart
     *
     * @var bool
     */
    protected $collectionRelation = false;

    /**
     * Get PhpWord
     *
     * @return \PhpOffice\PhpWord\PhpWord
     */
    public function getPhpWord()
    {
        return $this->phpWord;
    }

    /**
     * Set PhpWord as reference.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return void
     */
    public function setPhpWord(PhpWord $phpWord = null)
    {
        $this->phpWord = $phpWord;
    }

    /**
     * Get section number
     *
     * @return int
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * Set doc part.
     *
     * @param string $docPart
     * @param int $docPartId
     * @return void
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
     * @return int
     */
    public function getDocPartId()
    {
        return $this->docPartId;
    }

    /**
     * Return media element (image, object, link) container name
     *
     * @return string section|headerx|footerx|footnote|endnote
     */
    private function getMediaPart()
    {
        $mediaPart = $this->docPart;
        if ($mediaPart == 'Header' || $mediaPart == 'Footer') {
            $mediaPart .= $this->docPartId;
        }

        return strtolower($mediaPart);
    }

    /**
     * Get element index
     *
     * @return int
     */
    public function getElementIndex()
    {
        return $this->elementIndex;
    }

    /**
     * Set element index.
     *
     * @param int $value
     * @return void
     */
    public function setElementIndex($value)
    {
        $this->elementIndex = $value;
    }

    /**
     * Get element unique ID
     *
     * @return string
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * Set element unique ID from 6 first digit of md5.
     *
     * @return void
     */
    public function setElementId()
    {
        $this->elementId = substr(md5(rand()), 0, 6);
    }

    /**
     * Get relation Id
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * Set relation Id.
     *
     * @param int $value
     * @return void
     */
    public function setRelationId($value)
    {
        $this->relationId = $value;
    }

    /**
     * Get nested level
     *
     * @return int
     */
    public function getNestedLevel()
    {
        return $this->nestedLevel;
    }

    /**
     * Set parent container
     *
     * Passed parameter should be a container, except for Table (contain Row) and Row (contain Cell)
     *
     * @param \PhpOffice\PhpWord\Element\AbstractElement $container
     * @return void
     */
    public function setParentContainer(AbstractElement $container)
    {
        $this->parentContainer = substr(get_class($container), strrpos(get_class($container), '\\') + 1);

        // Set nested level
        $this->nestedLevel = $container->getNestedLevel();
        if ($this->parentContainer == 'Cell') {
            $this->nestedLevel++;
        }

        // Set phpword
        $this->setPhpWord($container->getPhpWord());

        // Set doc part
        if (!$this instanceof Footnote) {
            $this->setDocPart($container->getDocPart(), $container->getDocPartId());
        }

        $this->setMediaRelation();
        $this->setCollectionRelation();
    }

    /**
     * Set relation Id for media elements (link, image, object; legacy of OOXML)
     *
     * - Image element needs to be passed to Media object
     * - Icon needs to be set for Object element
     *
     * @return void
     */
    private function setMediaRelation()
    {
        if (!$this instanceof Link && !$this instanceof Image && !$this instanceof Object) {
            return;
        }

        $elementName = substr(get_class($this), strrpos(get_class($this), '\\') + 1);
        $mediaPart = $this->getMediaPart();
        $source = $this->getSource();
        $image = null;
        if ($this instanceof Image) {
            $image = $this;
        }
        $rId = Media::addElement($mediaPart, strtolower($elementName), $source, $image);
        $this->setRelationId($rId);

        if ($this instanceof Object) {
            $icon = $this->getIcon();
            $rId = Media::addElement($mediaPart, 'image', $icon, new Image($icon));
            $this->setImageRelationId($rId);
        }
    }

    /**
     * Set relation Id for elements that will be registered in the Collection subnamespaces.
     *
     * @return void
     */
    private function setCollectionRelation()
    {
        if ($this->collectionRelation === true && $this->phpWord instanceof PhpWord) {
            $elementName = substr(get_class($this), strrpos(get_class($this), '\\') + 1);
            $addMethod = "add{$elementName}";
            $rId = $this->phpWord->$addMethod($this);
            $this->setRelationId($rId);
        }
    }

    /**
     * Check if element is located in Section doc part (as opposed to Header/Footer)
     *
     * @return bool
     */
    public function isInSection()
    {
        return ($this->docPart == 'Section');
    }

    /**
     * Set new style value
     *
     * @param mixed $styleObject Style object
     * @param mixed $styleValue Style value
     * @param bool $returnObject Always return object
     * @return mixed
     */
    protected function setNewStyle($styleObject, $styleValue = null, $returnObject = false)
    {
        if (!is_null($styleValue) && is_array($styleValue)) {
            $styleObject->setStyleByArray($styleValue);
            $style = $styleObject;
        } else {
            $style = $returnObject ? $styleObject : $styleValue;
        }

        return $style;
    }

    /**
     * Set enum value
     *
     * @param mixed $value
     * @param array $enum
     * @param mixed $default
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     *
     * @todo Merge with the same method in AbstractStyle
     */
    protected function setEnumVal($value = null, $enum = array(), $default = null)
    {
        if ($value != null && trim($value) != '' && !empty($enum) && !in_array($value, $enum)) {
            throw new \InvalidArgumentException("Invalid style value: {$value}");
        } elseif ($value === null || trim($value) == '') {
            $value = $default;
        }

        return $value;
    }
}
