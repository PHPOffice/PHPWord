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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;

/**
 * ODText writer
 *
 * @since 0.7.0
 */
class ODText extends AbstractWriter implements WriterInterface
{
    /**
     * Create new ODText writer
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Create parts
        $parts = array('Content', 'Manifest', 'Meta', 'Mimetype', 'Styles');
        foreach ($parts as $part) {
            $partName = strtolower($part);
            $partClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Part\\' . $part;
            if (class_exists($partClass)) {
                $partObject = new $partClass();
                $partObject->setParentWriter($this);
                $this->writerParts[$partName] = $partObject;
            }
        }

        // Set package paths
        $this->mediaPaths = array('image' => 'Pictures/');
    }

    /**
     * Save PhpWord to file
     *
     * @param  string $filename
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function save($filename = null)
    {
        if (!is_null($this->phpWord)) {
            $filename = $this->getTempFile($filename);
            $objZip = $this->getZipArchive($filename);

            // Add section media files
            $sectionMedia = Media::getElements('section');
            if (!empty($sectionMedia)) {
                $this->addFilesToPackage($objZip, $sectionMedia);
            }

            // Add parts
            $objZip->addFromString('mimetype', $this->getWriterPart('mimetype')->writeMimetype());
            $objZip->addFromString('content.xml', $this->getWriterPart('content')->writeContent($this->phpWord));
            $objZip->addFromString('meta.xml', $this->getWriterPart('meta')->writeMeta($this->phpWord));
            $objZip->addFromString('styles.xml', $this->getWriterPart('styles')->writeStyles($this->phpWord));
            $objZip->addFromString('META-INF/manifest.xml', $this->getWriterPart('manifest')->writeManifest());

            // Close file
            if ($objZip->close() === false) {
                throw new Exception("Could not close zip file $filename.");
            }

            $this->cleanupTempFile();
        } else {
            throw new Exception("PhpWord object unassigned.");
        }
    }
}
