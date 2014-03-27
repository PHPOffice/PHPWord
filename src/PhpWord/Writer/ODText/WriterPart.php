<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Writer\IWriter;

/**
 * ODText writer part abstract
 */
abstract class WriterPart
{
    /**
     * Parent IWriter object
     *
     * @var \PhpOffice\PhpWord\Writer\IWriter
     */
    private $_parentWriter;

    /**
     * Set parent IWriter object
     *
     * @param \PhpOffice\PhpWord\Writer\IWriter $pWriter
     */
    public function setParentWriter(IWriter $pWriter = null)
    {
        $this->_parentWriter = $pWriter;
    }

    /**
     * Get parent IWriter object
     *
     * @return \PhpOffice\PhpWord\Writer\IWriter
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->_parentWriter)) {
            return $this->_parentWriter;
        } else {
            throw new Exception("No parent IWriter assigned.");
        }
    }
}
