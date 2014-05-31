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

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * IO Factory
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
        $class = 'PhpOffice\\PhpWord\\Writer\\' . $name;
        if (class_exists($class) && self::isConcreteClass($class)) {
            return new $class($phpWord);
        } else {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }
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
        $class = 'PhpOffice\\PhpWord\\Reader\\' . $name;
        if (class_exists($class) && self::isConcreteClass($class)) {
            return new $class();
        } else {
            throw new Exception("\"{$name}\" is not a valid reader.");
        }
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

    /**
     * Check if it's a concrete class (not abstract nor interface)
     *
     * @param string $class
     * @return bool
     */
    private static function isConcreteClass($class)
    {
        $reflection = new \ReflectionClass($class);

        return !$reflection->isAbstract() && !$reflection->isInterface();
    }
}
