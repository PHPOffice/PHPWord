<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader;

/**
 * Reader interface
 */
interface ReaderInterface
{
    /**
     * Can the current ReaderInterface read the file?
     *
     * @param  string $pFilename
     * @return boolean
     */
    public function canRead($pFilename);

    /**
     * Loads PhpWord from file
     *
     * @param string $pFilename
     */
    public function load($pFilename);
}
