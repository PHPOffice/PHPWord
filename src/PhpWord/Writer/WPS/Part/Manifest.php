<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\WPS\Media;

/**
 * WPS manifest part writer.
 *
 * @since 0.18.0
 */
class Manifest extends AbstractPart
{
    /**
     * Write manifest.xml file.
     */
    public function write(): string
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('manifest:manifest');

        // Write namespaces
        $xmlWriter->writeAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');

        // Basic document entries
        $this->writeManifestItem($xmlWriter, '/', 'application/vnd.wps-office.document');
        $this->writeManifestItem($xmlWriter, 'content.xml', 'text/xml');
        $this->writeManifestItem($xmlWriter, 'meta.xml', 'text/xml');

        // Media files
        $this->writeMediaFiles($xmlWriter);

        $xmlWriter->endElement(); // manifest:manifest

        return $xmlWriter->getData();
    }

    /**
     * Write manifest item.
     */
    private function writeManifestItem(XMLWriter $xmlWriter, string $href, string $mediaType): void
    {
        $xmlWriter->startElement('manifest:file-entry');
        $xmlWriter->writeAttribute('manifest:media-type', $mediaType);
        $xmlWriter->writeAttribute('manifest:full-path', $href);
        $xmlWriter->endElement();
    }

    /**
     * Write media files.
     */
    private function writeMediaFiles(XMLWriter $xmlWriter): void
    {
        $mediaParts = ['section', 'header', 'footer'];
        $writtenTargets = []; // Keep track of written targets to avoid duplicates

        foreach ($mediaParts as $partName) {
            $media = Media::getElements($partName);
            if (!empty($media)) {
                foreach ($media as $medium) {
                    $targetPath = 'Pictures/' . $medium['target'];
                    // Only write entry if it hasn't been written yet
                    if (!isset($writtenTargets[$targetPath]) && $medium['type'] == 'image') {
                        $this->writeManifestItem(
                            $xmlWriter,
                            $targetPath,
                            $this->getMediaType($medium['target'])
                        );
                        $writtenTargets[$targetPath] = true; // Mark as written
                    }
                }
            }
        }
    }

    /**
     * Get media type from file extension.
     */
    private function getMediaType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'bmp':
                return 'image/bmp';
            case 'tiff':
            case 'tif':
                return 'image/tiff';
            case 'svg':
                return 'image/svg+xml';
            default:
                return 'application/octet-stream';
        }
    }
}
