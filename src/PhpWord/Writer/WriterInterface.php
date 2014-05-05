<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer;

/**
 * Writer interface
 */
interface WriterInterface
{
    /**
     * Save PhpWord to file
     *
     * @param string $pFilename
     */
    public function save($pFilename = null);
}
