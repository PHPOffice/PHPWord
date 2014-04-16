<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * Word2007 writer part abstract class
 */
abstract class AbstractWriterPart
{
    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\WriterInterface
     */
    protected $parentWriter;

    /**
     * Set parent writer
     *
     * @param \PhpOffice\PhpWord\Writer\WriterInterface $pWriter
     */
    public function setParentWriter(WriterInterface $pWriter = null)
    {
        $this->parentWriter = $pWriter;
    }

    /**
     * Get parent writer
     *
     * @return \PhpOffice\PhpWord\Writer\WriterInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->parentWriter)) {
            return $this->parentWriter;
        } else {
            throw new Exception("No parent WriterInterface assigned.");
        }
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\PhpWord\Shared\XMLWriter
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
