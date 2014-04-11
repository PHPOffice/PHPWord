<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Abstract style class
 *
 * @since 0.9.2
 */
abstract class AbstractStyle
{
    /**
     * Index number in Style collection for named style
     *
     * This number starts from one and defined in Style::setStyleValues()
     *
     * @var integer|null
     */
    protected $index;

    /**
     * Get index number
     *
     * @return integer|null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set index number
     *
     * @param integer|null $value
     */
    public function setIndex($value = null)
    {
        $this->index = $this->setIntVal($value, $this->index);

        return $this;
    }

    /**
     * Set style value template method
     *
     * Some child classes have their own specific overrides
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setStyleValue($key, $value)
    {
        // Backward compability check for versions < 0.9.2 which use underscore
        // prefix for their private properties
        if (substr($key, 0, 1) == '_') {
            $key = substr($key, 1);
        }

        // Check if the set method is exists. Throws an exception?
        $method = 'set' . $key;
        if (method_exists($this, $method)) {
            $this->$method($value);
        }

        return $this;
    }

    /**
     * Set style by using associative array
     *
     * @param array $styles
     * @return self
     */
    public function setStyleByArray($styles = array())
    {
        foreach ($styles as $key => $value) {
            $this->setStyleValue($key, $value);
        }

        return $this;
    }

    /**
     * Set boolean value
     *
     * @param mixed $value
     * @param boolean|null $default
     * @return boolean|null
     */
    protected function setBoolVal($value, $default = null)
    {
        if (!is_bool($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set integer value
     *
     * @param mixed $value
     * @param integer|null $default
     * @return integer|null
     */
    protected function setIntVal($value, $default = null)
    {
        $value = intval($value);
        if (!is_int($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set float value
     *
     * @param mixed $value
     * @param float|null $default
     * @return float|null
     */
    protected function setFloatVal($value, $default = null)
    {
        $value = floatval($value);
        if (!is_float($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set enum value
     *
     * @param mixed $value
     * @param array $enum
     * @param mixed $default
     */
    protected function setEnumVal($value, $enum, $default = null)
    {
        if (!in_array($value, $enum)) {
            $value = $default;
        }

        return $value;
    }
}
