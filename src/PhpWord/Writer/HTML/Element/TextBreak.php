<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * TextBreak element HTML writer
 *
 * @since 0.10.0
 */
class TextBreak extends Element
{
    /**
     * Write text break
     *
     * @return string
     */
    public function write()
    {
        if ($this->withoutP) {
            $html = '<br />' . PHP_EOL;
        } else {
            $html = '<p>&nbsp;</p>' . PHP_EOL;
        }

        return $html;
    }
}
