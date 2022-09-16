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

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use ZipArchive;

/**
 * Test helper class.
 */
class TestHelperDOCX
{
    /**
     * Temporary file name.
     *
     * @var string
     */
    protected static $file;

    /**
     * Get document content.
     *
     * @since 0.12.0 Throws CreateTemporaryFileException.
     *
     * @param string $writerName
     *
     * @return \PhpOffice\PhpWordTests\XmlDocument
     */
    public static function getDocument(PhpWord $phpWord, $writerName = 'Word2007')
    {
        self::$file = tempnam(Settings::getTempDir(), 'PhpWord');
        if (false === self::$file) {
            throw new CreateTemporaryFileException();
        }

        if (!is_dir(Settings::getTempDir() . '/PhpWord_Unit_Test/')) {
            mkdir(Settings::getTempDir() . '/PhpWord_Unit_Test/');
        }

        $xmlWriter = IOFactory::createWriter($phpWord, $writerName);
        $xmlWriter->save(self::$file);

        $zip = new ZipArchive();
        $res = $zip->open(self::$file);
        if (true === $res) {
            $zip->extractTo(Settings::getTempDir() . '/PhpWord_Unit_Test/');
            $zip->close();
        }

        $doc = new XmlDocument(Settings::getTempDir() . '/PhpWord_Unit_Test/');
        if ($writerName === 'ODText') {
            $doc->setDefaultFile('content.xml');
        }

        return $doc;
    }

    /**
     * Clear document.
     */
    public static function clear(): void
    {
        if (self::$file && file_exists(self::$file)) {
            unlink(self::$file);
        }
        if (is_dir(Settings::getTempDir() . '/PhpWord_Unit_Test/')) {
            self::deleteDir(Settings::getTempDir() . '/PhpWord_Unit_Test/');
        }
    }

    /**
     * Delete directory.
     *
     * @param string $dir
     */
    public static function deleteDir($dir): void
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            } elseif (is_file($dir . '/' . $file)) {
                unlink($dir . '/' . $file);
            } elseif (is_dir($dir . '/' . $file)) {
                self::deleteDir($dir . '/' . $file);
            }
        }

        rmdir($dir);
    }

    /**
     * Get file.
     *
     * @return string
     */
    public static function getFile()
    {
        return self::$file;
    }
}
