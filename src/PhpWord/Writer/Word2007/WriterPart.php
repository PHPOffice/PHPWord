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
use PhpOffice\PhpWord\Shared\XMLWriter;

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
    protected $parentWriter;

    /**
     * Set parent writer
     *
     * @param IWriter $pWriter
     */
    public function setParentWriter(IWriter $pWriter = null)
    {
        $this->parentWriter = $pWriter;
    }

    /**
     * Get parent writer
     *
     * @return IWriter
     * @throws Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->parentWriter)) {
            return $this->parentWriter;
        } else {
            throw new Exception("No parent IWriter assigned.");
        }
    }

    /**
     * Get XML Writer
     *
     * @return XMLWriter
     */
    protected function getXmlWriter()
    {
        $useDiskCaching = false;
        if (!is_null($this->parentWriter)) {
            if ($this->parentWriter->getUseDiskCaching()) {
                $useDiskCaching = true;
            }
        }
        if ($useDiskCaching) {
            return new XMLWriter(XMLWriter::STORAGE_DISK, $this->parentWriter->getDiskCachingDirectory());
        } else {
            return new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }
    }
}
