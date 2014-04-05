<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Abstract element class
 *
 * @since 0.9.2
 */
abstract class Element
{
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
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
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
     * Check if element is located in section doc part (as opposed to header/footer)
     *
     * @return boolean
     */
    public function isInSection()
    {
        return ($this->docPart == 'section');
    }
}
