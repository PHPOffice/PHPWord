<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Style\NumberingLevel;

/**
 * Numbering style
 *
 * @link http://www.schemacentral.com/sc/ooxml/e-w_numbering.html
 * @link http://www.schemacentral.com/sc/ooxml/e-w_abstractNum-1.html
 * @link http://www.schemacentral.com/sc/ooxml/e-w_num-1.html
 * @since 0.10.0
 */
class Numbering extends AbstractStyle
{
    /**
     * Numbering definition instance ID
     *
     * @var int
     * @link http://www.schemacentral.com/sc/ooxml/e-w_num-1.html
     */
    private $numId;

    /**
     * Multilevel type singleLevel|multilevel|hybridMultilevel
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_val-67.html
     */
    private $type;

    /**
     * Numbering levels
     *
     * @var NumberingLevel[]
     */
    private $levels = array();

    /**
     * Get Id
     *
     * @return integer
     */
    public function getNumId()
    {
        return $this->numId;
    }

    /**
     * Set Id
     *
     * @param integer $value
     * @return self
     */
    public function setNumId($value)
    {
        $this->numId = $this->setIntVal($value, $this->numId);
        return $this;
    }

    /**
     * Get multilevel type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set multilevel type
     *
     * @param string $value
     * @return self
     */
    public function setType($value)
    {
        $enum = array('singleLevel', 'multilevel', 'hybridMultilevel');
        $this->type = $this->setEnumVal($value, $enum, $this->type);
        return $this;
    }

    /**
     * Get levels
     *
     * @return NumberingLevel[]
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * Set multilevel type
     *
     * @param array $values
     * @return self
     */
    public function setLevels($values)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $numberingLevel = new NumberingLevel();
                if (is_array($value)) {
                    $numberingLevel->setStyleByArray($value);
                    $numberingLevel->setLevel($key);
                }
                $this->levels[$key] = $numberingLevel;
            }
        }

        return $this;
    }
}
