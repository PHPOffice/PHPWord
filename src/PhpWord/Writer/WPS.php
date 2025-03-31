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

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\WPS\Media;
use PhpOffice\PhpWord\Writer\WPS\Part\AbstractPart;

/**
 * WPS writer.
 */
class WPS extends AbstractWriter implements WriterInterface
{
    /**
     * Create new WPS writer.
     */
    public function __construct(?PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Create parts
        $this->parts = [
            'Content' => 'content.xml',
            'Styles' => 'styles.xml',
            'Meta' => 'meta.xml',
            'Manifest' => 'META-INF/manifest.xml',
        ];
        foreach (array_keys($this->parts) as $partName) {
            $partClass = "PhpOffice\\PhpWord\\Writer\\WPS\\Part\\{$partName}";
            if (class_exists($partClass)) {
                /** @var AbstractPart $part */
                $part = new $partClass();
                $part->setParentWriter($this);
                $this->writerParts[strtolower($partName)] = $part;
            }
        }

        // Set package paths
        $this->mediaPaths = ['image' => 'Pictures/'];
    }

    /**
     * Save PhpWord to file.
     */
    public function save(string $filename): void
    {
        $filename = $this->getTempFile($filename);
        $zip = $this->getZipArchive($filename);

        // Add section media files
        $sectionMedia = Media::getElements('section');
        if (!empty($sectionMedia)) {
            $this->addFilesToPackage($zip, $sectionMedia);
        }

        // Add header/footer media files
        $headerMedia = Media::getElements('header');
        if (!empty($headerMedia)) {
            $this->addFilesToPackage($zip, $headerMedia);
        }

        $footerMedia = Media::getElements('footer');
        if (!empty($footerMedia)) {
            $this->addFilesToPackage($zip, $footerMedia);
        }

        // Make sure the META-INF directory exists
        $zip->addEmptyDir('META-INF');

        // Write parts
        foreach ($this->parts as $partName => $fileName) {
            if ($fileName === '') {
                continue;
            }
            $part = $this->getWriterPart($partName);
            if (!$part instanceof AbstractPart) {
                continue;
            }

            $zip->addFromString($fileName, $part->write());
        }

        // Close zip archive and cleanup temp file
        $zip->close();
        $this->cleanupTempFile();
    }
}
