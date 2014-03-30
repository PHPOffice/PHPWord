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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * ODText manifest part writer
 */
class Manifest extends WriterPart
{
    /**
     * Write Manifest file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeManifest(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // manifest:manifest
        $xmlWriter->startElement('manifest:manifest');
        $xmlWriter->writeAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');
        $xmlWriter->writeAttribute('manifest:version', '1.2');

        // manifest:file-entry
        $xmlWriter->startElement('manifest:file-entry');
        $xmlWriter->writeAttribute('manifest:media-type', 'application/vnd.oasis.opendocument.text');
        $xmlWriter->writeAttribute('manifest:version', '1.2');
        $xmlWriter->writeAttribute('manifest:full-path', '/');
        $xmlWriter->endElement();
        // manifest:file-entry
        $xmlWriter->startElement('manifest:file-entry');
        $xmlWriter->writeAttribute('manifest:media-type', 'text/xml');
        $xmlWriter->writeAttribute('manifest:full-path', 'content.xml');
        $xmlWriter->endElement();
        // manifest:file-entry
        $xmlWriter->startElement('manifest:file-entry');
        $xmlWriter->writeAttribute('manifest:media-type', 'text/xml');
        $xmlWriter->writeAttribute('manifest:full-path', 'meta.xml');
        $xmlWriter->endElement();
        // manifest:file-entry
        $xmlWriter->startElement('manifest:file-entry');
        $xmlWriter->writeAttribute('manifest:media-type', 'text/xml');
        $xmlWriter->writeAttribute('manifest:full-path', 'styles.xml');
        $xmlWriter->endElement();

        // Not used yet. Legacy from PHPExcel
        // @codeCoverageIgnoreStart
        for ($i = 0; $i < $this->getParentWriter()->getDrawingHashTable()->count(); ++$i) {
            if ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_Drawing) {
                $extension = strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getExtension());
                $mimeType = $this->getImageMimeType($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getPath());

                $xmlWriter->startElement('manifest:file-entry');
                $xmlWriter->writeAttribute('manifest:media-type', $mimeType);
                $xmlWriter->writeAttribute('manifest:full-path', 'Pictures/' . str_replace(' ', '_', $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()));
                $xmlWriter->endElement();
            } elseif ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_MemoryDrawing) {
                $extension = strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType());
                $extension = explode('/', $extension);
                $extension = $extension[1];

                $mimeType = $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType();

                $xmlWriter->startElement('manifest:file-entry');
                $xmlWriter->writeAttribute('manifest:media-type', $mimeType);
                $xmlWriter->writeAttribute('manifest:full-path', 'Pictures/' . str_replace(' ', '_', $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()));
                $xmlWriter->endElement();
            }
        }
        // @codeCoverageIgnoreEnd

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }


    /**
     * Get image mime type
     *
     * @param string $pFile Filename
     * @return string Mime Type
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    private function getImageMimeType($pFile = '')
    {
        if (file_exists($pFile)) {
            $image = getimagesize($pFile);
            return image_type_to_mime_type($image[2]);
        } else {
            throw new Exception("File $pFile does not exist");
        }
    }
}
