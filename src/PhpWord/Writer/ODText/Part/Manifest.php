<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Media;

/**
 * ODText manifest part writer
 */
class Manifest extends AbstractPart
{
    /**
     * Write Manifest file to XML format
     *
     * @return string XML Output
     */
    public function writeManifest()
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // manifest:manifest
        $xmlWriter->startElement('manifest:manifest');
        $xmlWriter->writeAttribute('manifest:version', '1.2');
        $xmlWriter->writeAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');

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

        // Media files
        $media = Media::getElements('section');
        foreach ($media as $medium) {
            if ($medium['type'] == 'image') {
                $xmlWriter->startElement('manifest:file-entry');
                $xmlWriter->writeAttribute('manifest:media-type', $medium['imageType']);
                $xmlWriter->writeAttribute('manifest:full-path', 'Pictures/' . $medium['target']);
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->endElement(); // manifest:manifest

        return $xmlWriter->getData();
    }
}
