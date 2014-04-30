<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Shared\String;

/**
 * Abstract style class
 *
 * @since 0.10.0
 */
abstract class AbstractStyle
{
    /**
     * Style name
     *
     * @var string
     */
    protected $styleName;

    /**
     * Index number in Style collection for named style
     *
     * This number starts from one and defined in Style::setStyleValues()
     *
     * @var integer|null
     */
    protected $index;

    /**
     * Get style name
     *
     * @return string
     */
    public function getStyleName()
    {
        return $this->styleName;
    }

    /**
     * Set style name
     *
     * @param string $value
     * @return self
     */
    public function setStyleName($value)
    {
        $this->styleName = $value;

        return $this;
    }

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
     * @return self
     */
    public function setIndex($value = null)
    {
        $this->index = $this->setIntVal($value, $this->index);

        return $this;
    }

    /**
     * Set style value template method
     *
     * Some child classes have their own specific overrides.
     * Backward compability check for versions < 0.10.0 which use underscore
     * prefix for their private properties.
     * Check if the set method is exists. Throws an exception?
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setStyleValue($key, $value)
    {
        $method = 'set' . String::removeUnderscorePrefix($key);
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
     * Set default for null and empty value
     *
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    protected function setNonEmptyVal($value, $default)
    {
        if (is_null($value) || $value == '') {
            $value = $default;
        }

        return $value;
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
     * Set numeric value
     *
     * @param mixed $value
     * @param integer|float|null $default
     * @return integer|float|null
     */
    protected function setNumericVal($value, $default = null)
    {
        if (!is_numeric($value)) {
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
