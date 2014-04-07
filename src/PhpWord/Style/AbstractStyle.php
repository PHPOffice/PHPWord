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
     * Set style value template method
     *
     * Some child classes have their own specific overrides
     *
     * @param string $key
     * @param string $value
     *
     * @todo Implement type check mechanism, e.g. boolean, integer, enum, defaults
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
    }
}
