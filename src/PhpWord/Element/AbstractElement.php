<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style;

/**
 * Element abstract class
 *
 * @since 0.10.0
 */
abstract class AbstractElement
{
    /**
     * Container type section|header|footer|cell|textrun|footnote|endnote
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
    protected $docPart = 'section';

    /**
     * Document part Id
     *
     * For header and footer, this will be = ($sectionId - 1) * 3 + $index
     * because the max number of header/footer in every page is 3, i.e.
     * AUTO, FIRST, and EVEN (AUTO = ODD)
     *
     * @var integer
     */
    protected $docPartId = 1;

    /**
     * Index of element in the elements collection (start with 1)
     *
     * @var integer
     */
    protected $elementIndex = 1;

    /**
     * Unique Id for element
     *
     * @var integer
     */
    protected $elementId;

    /**
     * Relation Id
     *
     * @var int
     */
    protected $relationId;

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
     * @param int $rId
     */
    public function setRelationId($rId)
    {
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
                $styleObject->setStyleValue($key, $value);
            }
            $style = $styleObject;
        } else {
            $style = $returnObject ? $styleObject : $styleValue;
        }

        return $style;
    }
}
