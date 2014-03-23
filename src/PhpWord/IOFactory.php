<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exceptions\Exception;

abstract class IOFactory
{
    /**
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @param string $name
     * @return \PhpOffice\PhpWord\Writer\IWriter
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public static function createWriter(PhpWord $phpWord, $name)
    {
        if ($name !== 'IWriter' && $name !== 'ODText' && $name !== 'RTF' && $name !== 'Word2007') {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }

        $fqName = "PhpOffice\\PhpWord\\Writer\\{$name}";
        return new $fqName($phpWord);
    }

    /**
     * @param string $name
     * @return \PhpOffice\PhpWord\Reader\IReader
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public static function createReader($name)
    {
        if ($name !== 'IReader' && $name !== 'Word2007') {
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
     * @return \PhpOffice\PhpWord
     */
    public static function load($filename, $readerName = 'Word2007')
    {
        $reader = self::createReader($readerName);
        return $reader->load($filename);
    }
}
