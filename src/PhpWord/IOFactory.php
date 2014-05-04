<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * IO factory
 */
abstract class IOFactory
{
    /**
     * Create new writer
     *
     * @param PhpWord $phpWord
     * @param string $name
     * @return \PhpOffice\PhpWord\Writer\WriterInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public static function createWriter(PhpWord $phpWord, $name = 'Word2007')
    {
        if (!in_array($name, array('WriterInterface', 'Word2007', 'ODText', 'RTF', 'HTML', 'PDF'))) {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }

        $fqName = "PhpOffice\\PhpWord\\Writer\\{$name}";
        return new $fqName($phpWord);
    }

    /**
     * Create new reader
     *
     * @param string $name
     * @return \PhpOffice\PhpWord\Reader\ReaderInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public static function createReader($name = 'Word2007')
    {
        if (!in_array($name, array('ReaderInterface', 'Word2007', 'ODText'))) {
            throw new Exception("\"{$name}\" is not a valid reader.");
        }

        $fqName = "PhpOffice\\PhpWord\\Reader\\{$name}";
        return new $fqName();
    }

    /**
     * Loads PhpWord from file
     *
     * @param string $filename The name of the file
     * @param string $readerName
     * @return PhpWord
     */
    public static function load($filename, $readerName = 'Word2007')
    {
        $reader = self::createReader($readerName);
        return $reader->load($filename);
    }
}
