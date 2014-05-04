<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * Footnote element writer
 *
 * @since 0.10.0
 */
class Footnote extends Note
{
    /**
     * Write element
     */
    public function write()
    {
        $this->referenceType = 'footnoteReference';
        parent::write();
    }
}
