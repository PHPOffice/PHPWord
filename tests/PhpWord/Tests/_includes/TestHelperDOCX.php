<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * Test helper class
 */
class TestHelperDOCX
{
    /**
     * Temporary file name
     *
     * @var string
     */
    static protected $file;

    /**
     * Get document content
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @param string $writerName
     * @return \PhpOffice\PhpWord\Tests\XmlDocument
     */
    public static function getDocument(PhpWord $phpWord, $writerName = 'Word2007')
    {
        self::$file = tempnam(sys_get_temp_dir(), 'PhpWord');
        if (!is_dir(sys_get_temp_dir() . '/PhpWord_Unit_Test/')) {
            mkdir(sys_get_temp_dir() . '/PhpWord_Unit_Test/');
        }

        $xmlWriter = IOFactory::createWriter($phpWord, $writerName);
        $xmlWriter->save(self::$file);

        $zip = new \ZipArchive;
        $res = $zip->open(self::$file);
        if ($res === true) {
            $zip->extractTo(sys_get_temp_dir() . '/PhpWord_Unit_Test/');
            $zip->close();
        }

        return new XmlDocument(sys_get_temp_dir() . '/PhpWord_Unit_Test/');
    }

    /**
     * Clear document
     */
    public static function clear()
    {
        if (file_exists(self::$file)) {
            unlink(self::$file);
        }
        if (is_dir(sys_get_temp_dir() . '/PhpWord_Unit_Test/')) {
            self::deleteDir(sys_get_temp_dir() . '/PhpWord_Unit_Test/');
        }
    }

    /**
     * Delete directory
     *
     * @param string $dir
     */
    public static function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } elseif (is_file($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            } elseif (is_dir($dir . "/" . $file)) {
                self::deleteDir($dir . "/" . $file);
            }
        }

        rmdir($dir);
    }

    /**
     * Get file
     *
     * @return string
     */
    public static function getFile()
    {
        return self::$file;
    }
}
