<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Writer\IWriter;

/**
 * Word2007 writer part abstract class
 */
abstract class WriterPart
{
    /**
     * Parent writer
     *
     * @var IWriter
     */
    private $_parentWriter;

    /**
     * Set parent writer
     *
     * @param IWriter $pWriter
     */
    public function setParentWriter(IWriter $pWriter = null)
    {
        $this->_parentWriter = $pWriter;
    }

    /**
     * Get parent writer
     *
     * @return IWriter
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
