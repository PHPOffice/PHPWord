<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
