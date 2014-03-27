<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\PhpWord;

/**
 * ODText mimetype part writer
 */
class Mimetype extends WriterPart
{
    /**
     * Write Mimetype to Text format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string Text Output
     */
    public function writeMimetype(PhpWord $phpWord = null)
    {
        return 'application/vnd.oasis.opendocument.text';
    }
}
