<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class TestHelperDOCX
{
    /** @var string $file */
    static protected $file;

    /**
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

    public static function clear()
    {
        if (\file_exists(self::$file)) {
            unlink(self::$file);
        }
        if (is_dir(sys_get_temp_dir() . '/PhpWord_Unit_Test/')) {
            self::deleteDir(sys_get_temp_dir() . '/PhpWord_Unit_Test/');
        }
    }

    /**
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
     * @return string
     */
    public static function getFile()
    {
        return self::$file;
    }
}
