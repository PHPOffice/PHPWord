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

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\ePub3\Part\AbstractPart;

/**
 * ePub3 writer.
 */
class ePub3 extends AbstractWriter implements WriterInterface
{
    /**
     * Create new ePub3 writer.
     *
     * @param \PhpOffice\PhpWord\PhpWord
     */
    public function __construct(?PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Create parts
        $this->parts = [
            'Mimetype' => 'mimetype',
            'Content' => 'content.opf',
            'Toc' => 'toc.ncx',
            'Styles' => 'styles.css',
            'Manifest' => 'META-INF/container.xml',
        ];
        foreach (array_keys($this->parts) as $partName) {
            $partClass = static::class . '\\Part\\' . $partName;
            if (class_exists($partClass)) {
                /** @var \PhpOffice\PhpWord\Writer\ePub3\Part\AbstractPart $partObject Type hint */
                $partObject = new $partClass();
                $partObject->setParentWriter($this);
                $this->writerParts[strtolower($partName)] = $partObject;
            }
        }

        // Set package paths
        $this->mediaPaths = ['image' => 'Images/', 'object' => 'Objects/'];
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