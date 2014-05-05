<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * Endnote element writer
 *
 * @since 0.10.0
 */
class Endnote extends Footnote
{
    /**
     * Reference type
     *
     * @var string
     */
    protected $referenceType = 'endnoteReference';
}
