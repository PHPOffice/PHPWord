<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

/**
 * ODText mimetype part writer
 */
class Mimetype extends AbstractPart
{
    /**
     * Write Mimetype to Text format
     *
     * @return string Text Output
     */
    public function writeMimetype()
    {
        return 'application/vnd.oasis.opendocument.text';
    }
}
