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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;

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
     * Document part type: section|header|footer
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
     * Get PhpWord
     *
     * @return \PhpOffice\PhpWord\PhpWord
     */
    public function getPhpWord()
    {
        return $this->phpWord;
    }

    /**
     * Set PhpWord as reference
     *
     * @param \PhpOffice\PhpWord\PhpWord
     */
    public function setPhpWord(PhpWord &$phpWord = null)
    {
        $this->phpWord = &$phpWord;
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
     * Set doc part
     *
     * @param string $docPart
     * @param int $docPartId
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
     * Get element index
     *
     * @return int
     */
    public function getElementIndex()
    {
        return $this->elementIndex;
    }

    /**
     * Set element index
     *
     * @param int $value
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
     * Set element unique ID from 6 first digit of md5
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
     * Set relation Id
     *
     * @param int $value
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
     * Set nested level
     *
     * @param int $value
     */
    public function setNestedLevel($value)
    {
        $this->nestedLevel = $value;
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
     * Set style value
     *
     * @param mixed $styleObject Style object
     * @param mixed $styleValue Style value
     * @param bool $returnObject Always return object
     * @return mixed
     */
    protected function setStyle($styleObject, $styleValue = null, $returnObject = false)
    {
        if (!is_null($styleValue) && is_array($styleValue)) {
            $styleObject->setStyleByArray($styleValue);
            $style = $styleObject;
        } else {
            $style = $returnObject ? $styleObject : $styleValue;
        }

        return $style;
    }
}
