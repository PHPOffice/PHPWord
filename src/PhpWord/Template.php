<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Shared\String;

/**
 * Template
 */
class Template
{
    /**
     * ZipArchive object
     *
     * @var \ZipArchive
     */
    private $_objZip;

    /**
     * Temporary file name
     *
     * @var string
     */
    private $_tempFileName;

    /**
     * Document XML
     *
     * @var string
     */
    private $_documentXML;


    /**
     * Create a new Template Object
     *
     * @param string $strFilename
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function __construct($strFilename)
    {
        $this->_tempFileName = tempnam(sys_get_temp_dir(), '');
        if ($this->_tempFileName === false) {
            throw new Exception('Could not create temporary file with unique name in the default temporary directory.');
        }

        // Copy the source File to the temp File
        if (!copy($strFilename, $this->_tempFileName)) {
            throw new Exception("Could not copy the template from {$strFilename} to {$this->_tempFileName}.");
        }

        $this->_objZip = new \ZipArchive();
        $this->_objZip->open($this->_tempFileName);

        $this->_documentXML = $this->_objZip->getFromName('word/document.xml');
    }

    /**
     * Applies XSL style sheet to template's parts
     *
     * @param \DOMDocument $xslDOMDocument
     * @param array $xslOptions
     * @param string $xslOptionsURI
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function applyXslStyleSheet(&$xslDOMDocument, $xslOptions = array(), $xslOptionsURI = '')
    {
        $processor = new \XSLTProcessor();

        $processor->importStylesheet($xslDOMDocument);

        if ($processor->setParameter($xslOptionsURI, $xslOptions) === false) {
            throw new Exception('Could not set values for the given XSL style sheet parameters.');
        }

        $xmlDOMDocument = new \DOMDocument();
        if ($xmlDOMDocument->loadXML($this->_documentXML) === false) {
            throw new Exception('Could not load XML from the given template.');
        }

        $xmlTransformed = $processor->transformToXml($xmlDOMDocument);
        if ($xmlTransformed === false) {
            throw new Exception('Could not transform the given XML document.');
        }

        $this->_documentXML = $xmlTransformed;
    }

    /**
     * Set a Template value
     *
     * @param mixed $search
     * @param mixed $replace
     * @param integer $limit
     */
    public function setValue($search, $replace, $limit = -1)
    {
        $pattern = '|\$\{([^\}]+)\}|U';
        preg_match_all($pattern, $this->_documentXML, $matches);
        foreach ($matches[0] as $value) {
            $valueCleaned = preg_replace('/<[^>]+>/', '', $value);
            $valueCleaned = preg_replace('/<\/[^>]+>/', '', $valueCleaned);
            $this->_documentXML = str_replace($value, $valueCleaned, $this->_documentXML);
        }

        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${' . $search . '}';
        }

        if (!is_array($replace)) {
            if (!String::isUTF8($replace)) {
                $replace = utf8_encode($replace);
            }
            $replace = htmlspecialchars($replace);
        } else {
            foreach ($replace as $key => $value) {
                $replace[$key] = htmlspecialchars($value);
            }
        }

        $regExpDelim = '/';
        $escapedSearch = preg_quote($search, $regExpDelim);
        $this->_documentXML = preg_replace("{$regExpDelim}{$escapedSearch}{$regExpDelim}u", $replace, $this->_documentXML, $limit);
    }

    /**
     * Returns array of all variables in template
     */
    public function getVariables()
    {
        preg_match_all('/\$\{(.*?)}/i', $this->_documentXML, $matches);
        return $matches[1];
    }

    /**
     * Find the start position of the nearest table row before $offset
     *
     * @param int $offset
     * @return int
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    private function _findRowStart($offset)
    {
        $rowStart = strrpos($this->_documentXML, "<w:tr ", ((strlen($this->_documentXML) - $offset) * -1));
        if (!$rowStart) {
            $rowStart = strrpos($this->_documentXML, "<w:tr>", ((strlen($this->_documentXML) - $offset) * -1));
        }
        if (!$rowStart) {
            throw new Exception("Can not find the start position of the row to clone.");
        }
        return $rowStart;
    }

    /**
     * Find the end position of the nearest table row after $offset
     *
     * @param int $offset
     * @return int
     */
    private function _findRowEnd($offset)
    {
        $rowEnd = strpos($this->_documentXML, "</w:tr>", $offset) + 7;
        return $rowEnd;
    }

    /**
     * Get a slice of a string
     *
     * @param int $startPosition
     * @param int $endPosition
     * @return string
     */
    private function _getSlice($startPosition, $endPosition = 0)
    {
        if (!$endPosition) {
            $endPosition = strlen($this->_documentXML);
        }
        return substr($this->_documentXML, $startPosition, ($endPosition - $startPosition));
    }

    /**
     * Clone a table row in a template document
     *
     * @param string $search
     * @param int $numberOfClones
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function cloneRow($search, $numberOfClones)
    {
        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${' . $search . '}';
        }

        $tagPos = strpos($this->_documentXML, $search);
        if (!$tagPos) {
            throw new Exception("Can not clone row, template variable not found or variable contains markup.");
        }

        $rowStart = $this->_findRowStart($tagPos);
        $rowEnd = $this->_findRowEnd($tagPos);
        $xmlRow = $this->_getSlice($rowStart, $rowEnd);

        // Check if there's a cell spanning multiple rows.
        if (preg_match('#<w:vMerge w:val="restart"/>#', $xmlRow)) {
            $extraRowStart = $rowEnd;
            $extraRowEnd = $rowEnd;
            while (true) {
                $extraRowStart = $this->_findRowStart($extraRowEnd + 1);
                $extraRowEnd = $this->_findRowEnd($extraRowEnd + 1);

                // If extraRowEnd is lower then 7, there was no next row found.
                if ($extraRowEnd < 7) {
                    break;
                }

                // If tmpXmlRow doesn't contain continue, this row is no longer part of the spanned row.
                $tmpXmlRow = $this->_getSlice($extraRowStart, $extraRowEnd);
                if (!preg_match('#<w:vMerge/>#', $tmpXmlRow) && !preg_match('#<w:vMerge w:val="continue" />#', $tmpXmlRow)) {
                    break;
                }
                // This row was a spanned row, update $rowEnd and search for the next row.
                $rowEnd = $extraRowEnd;
            }
            $xmlRow = $this->_getSlice($rowStart, $rowEnd);
        }

        $result = $this->_getSlice(0, $rowStart);
        for ($i = 1; $i <= $numberOfClones; $i++) {
            $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlRow);
        }
        $result .= $this->_getSlice($rowEnd);

        $this->_documentXML = $result;
    }

    /**
     * Save XML to temporary file
     *
     * @return string
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function save()
    {
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);

        // Close zip file
        if ($this->_objZip->close() === false) {
            throw new Exception('Could not close zip file.');
        }

        return $this->_tempFileName;
    }

    /**
     * Save XML to defined name
     *
     * @param string $strFilename
     */
    public function saveAs($strFilename)
    {
        $tempFilename = $this->save();

        if (\file_exists($strFilename)) {
            unlink($strFilename);
        }

        rename($tempFilename, $strFilename);
    }
}
