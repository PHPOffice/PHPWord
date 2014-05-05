<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

/**
 * TextBreak element RTF writer
 *
 * @since 0.10.0
 */
class TextBreak extends Element
{
    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        $this->parentWriter->setLastParagraphStyle();

        return '\par' . PHP_EOL;
    }
}
