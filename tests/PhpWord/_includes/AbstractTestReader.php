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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

/**
 * Base class for Word2007 reader tests
 */
abstract class AbstractTestReader extends \PHPUnit\Framework\TestCase
{
    private $parts = array(
        'styles'     => array('class' => 'PhpOffice\PhpWord\Reader\Word2007\Styles',      'xml' => '<w:styles xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:docDefaults><w:rPrDefault><w:rPr><w:sz w:val="24"/></w:rPr></w:rPrDefault></w:docDefaults>{toReplace}</w:styles>'),
        'document'   => array('class' => 'PhpOffice\PhpWord\Reader\Word2007\Document',    'xml' => '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>{toReplace}</w:body></w:document>'),
        'footnotes'  => array('class' => 'PhpOffice\PhpWord\Reader\Word2007\Footnotes',   'xml' => '<w:footnotes xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">{toReplace}</w:footnotes>'),
        'endnotes'   => array('class' => 'PhpOffice\PhpWord\Reader\Word2007\Endnotes',    'xml' => '<w:endnotes xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">{toReplace}</w:endnotes>'),
        'settings'   => array('class' => 'PhpOffice\PhpWord\Reader\Word2007\Settings',    'xml' => '<w:comments xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">{toReplace}</w:comments>'),
    );

    /**
     * Builds a PhpWord instance based on the xml passed
     *
     * @param string $documentXml
     * @param null|string $stylesXml
     * @return \PhpOffice\PhpWord\PhpWord
     */
    protected function getDocumentFromString(array $partXmls = array())
    {
        $file = __DIR__ . '/../_files/temp.docx';
        $zip = new \ZipArchive();
        $zip->open($file, \ZipArchive::CREATE);
        foreach ($this->parts as $partName => $part) {
            if (array_key_exists($partName, $partXmls)) {
                $zip->addFromString("{$partName}.xml", str_replace('{toReplace}', $partXmls[$partName], $this->parts[$partName]['xml']));
            }
        }
        $zip->close();

        $phpWord = new PhpWord();
        foreach ($this->parts as $partName => $part) {
            if (array_key_exists($partName, $partXmls)) {
                $className = $this->parts[$partName]['class'];
                $reader = new $className($file, "{$partName}.xml");
                $reader->read($phpWord);
            }
        }
        unlink($file);

        return $phpWord;
    }
}
