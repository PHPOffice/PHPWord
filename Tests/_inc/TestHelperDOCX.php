<?php
namespace PHPWord\Tests;

use PHPWord;

class TestHelperDOCX
{
    /**
     * @var string
     */
    protected static $file;

    /**
     * @param \PHPWord $PHPWord
     * @param string $writer
     * @return \PHPWord\Tests\XmlDocument
     */
    public static function getDocument(PHPWord $PHPWord, $writer = 'Word2007')
    {
        self::$file = tempnam(sys_get_temp_dir(), 'PHPWord');
        if (!is_dir(sys_get_temp_dir() . '/PHPWord_Unit_Test/')) {
            mkdir(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
        }

        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, $writer);
        $objWriter->save(self::$file);

        $zip = new \ZipArchive;
        $res = $zip->open(self::$file);
        if ($res === true) {
            $zip->extractTo(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
            $zip->close();
        }

        return new XmlDocument(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
    }

    public static function clear()
    {
        if (file_exists(self::$file)) {
            unlink(self::$file);
        }
        if (is_dir(sys_get_temp_dir() . '/PHPWord_Unit_Test/')) {
            self::deleteDir(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
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