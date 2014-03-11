<?php
namespace PHPWord\Tests;

use PHPWord;
use DOMDocument;

class TestHelperDOCX
{
    static protected $file;

    /**
     * @param \PHPWord $PHPWord
     * @return \PHPWord\Tests\Xml_Document
     */
    public static function getDocument(PHPWord $PHPWord)
    {
        self::$file = tempnam(sys_get_temp_dir(), 'PHPWord');
        if (!is_dir(sys_get_temp_dir() . '/PHPWord_Unit_Test/')) {
            mkdir(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
        }

        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save(self::$file);

        $zip = new \ZipArchive;
        $res = $zip->open(self::$file);
        if ($res === true) {
            $zip->extractTo(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
            $zip->close();
        }

        return new Xml_Document(sys_get_temp_dir() . '/PHPWord_Unit_Test/');
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
            } else if (is_file($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            } else if (is_dir($dir . "/" . $file)) {
                self::deleteDir($dir . "/" . $file);
            }
        }

        rmdir($dir);
    }
}

class Xml_Document
{
    /** @var string $path */
    private $path;

    /** @var \DOMDocument $dom */
    private $dom;

    /** @var \DOMXpath $xpath */
    private $xpath;

    /** @var string $file */
    private $file;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = realpath($path);
    }

    /**
     * @param string $file
     * @return \DOMDocument
     */
    public function getFileDom($file = 'word/document.xml')
    {
        if (null !== $this->dom && $file === $this->file) {
            return $this->dom;
        }

        $this->xpath = null;
        $this->file = $file;

        $file = $this->path . '/' . $file;
        $this->dom = new DOMDocument();
        $this->dom->load($file);
        return $this->dom;
    }

    /**
     * @param string $path
     * @param string $file
     * @return \DOMElement
     */
    public function getElement($path, $file = 'word/document.xml')
    {
        if ($this->dom === null || $file !== $this->file) {
            $this->getFileDom($file);
        }

        if (null === $this->xpath) {
            $this->xpath = new \DOMXpath($this->dom);

        }

        $elements = $this->xpath->query($path);
        return $elements->item(0);
    }
}