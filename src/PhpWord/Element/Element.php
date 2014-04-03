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
}
