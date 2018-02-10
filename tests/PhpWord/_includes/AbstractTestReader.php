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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Reader\Word2007\Document;

/**
 * Base class for Word2007 reader tests
 */
abstract class AbstractTestReader extends \PHPUnit\Framework\TestCase
{
    /**
     * Builds a PhpWord instance based on the xml passed
     *
     * @param string $documentXml
     * @return \PhpOffice\PhpWord\PhpWord
     */
    protected function getDocumentFromString($documentXml)
    {
        $phpWord = new PhpWord();
        $file = __DIR__ . '/../_files/temp.docx';
        $zip = new \ZipArchive();
        $zip->open($file, \ZipArchive::CREATE);
        $zip->addFromString('document.xml', '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>' . $documentXml . '</w:body></w:document>');
        $zip->close();
        $documentReader = new Document($file, 'document.xml');
        $documentReader->read($phpWord);
        unlink($file);

        return $phpWord;
    }

    /**
     * Returns the element at position $index in the array
     *
     * @param array $array
     * @param number $index
     * @return mixed
     */
    protected function get(array $array, $index = 0)
    {
        return $array[$index];
    }
}
