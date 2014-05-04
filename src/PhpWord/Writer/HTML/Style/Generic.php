<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

/**
 * Generic style writer
 *
 * @since 0.10.0
 */
class Generic extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $css = array();

        if (is_array($this->style) && !empty($this->style)) {
            $css = $this->style;
        }

        return $this->assembleCss($css);
    }
}
