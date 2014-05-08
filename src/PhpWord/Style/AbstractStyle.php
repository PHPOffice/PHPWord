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
     * @var int|null
     */
    protected $index;

    /**
     * Aliases
     *
     * @var array
     */
    protected $aliases = array();

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
     * @return int|null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set index number
     *
     * @param int|null $value
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
        if (isset($this->aliases[$key])) {
            $key = $this->aliases[$key];
        }
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
     * @param int|float|null $default
     * @return int|float|null
     */
    protected function setNumericVal($value, $default = null)
    {
        if (!is_numeric($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set float value: Convert string that contains only numeric into integer
     *
     * @param mixed $value
     * @param int|null $default
     * @return int|null
     */
    protected function setIntVal($value, $default = null)
    {
        if (is_string($value) && (preg_match('/[^\d]/', $value) == 0)) {
            $value = intval($value);
        }
        if (!is_int($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set float value: Convert string that contains only numeric into float
     *
     * @param mixed $value
     * @param float|null $default
     * @return float|null
     */
    protected function setFloatVal($value, $default = null)
    {
        if (is_string($value) && (preg_match('/[^\d\.\,]/', $value) == 0)) {
            $value = floatval($value);
        }
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
    protected function setEnumVal($value = null, $enum = array(), $default = null)
    {
        if (!is_null($value) && !empty($enum) && !in_array($value, $enum)) {
            throw new \InvalidArgumentException('Invalid style value.');
        } elseif (is_null($value)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set object value
     *
     * @param mixed $value
     * @param string $styleName
     * @param mixed $style
     */
    protected function setObjectVal($value, $styleName, &$style)
    {
        $styleClass = substr(get_class($this), 0, strrpos(get_class($this), '\\')) . '\\' . $styleName;
        if (is_array($value)) {
            if (!$style instanceof $styleClass) {
                $style = new $styleClass();
            }
            $style->setStyleByArray($value);
        } else {
            $style = $value;
        }

        return $style;
    }

    /**
     * Set style using associative array
     *
     * @param array $style
     * @deprecated 0.11.0
     * @codeCoverageIgnore
     */
    public function setArrayStyle(array $style = array())
    {
        return $this->setStyleByArray($style);
    }
}
