<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

/**
 * TextBreak element RTF writer
 *
 * @since 0.10.0
 */
class Title extends Element
{
    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        $rtfText = '';
        $rtfText .= '\pard\nowidctlpar' . PHP_EOL;
        $rtfText .= $this->element->getText();
        $rtfText .= '\par' . PHP_EOL;

        return $rtfText;
    }
}
