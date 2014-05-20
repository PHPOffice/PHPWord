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
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Shared\ZipArchive;

/**
 * Template
 */
class Template
{
    /**
     * ZipArchive object
     *
     * @var mixed
     */
    private $zipClass;

    /**
     * Temporary file name
     *
     * @var string
     */
    private $tempFileName;

    /**
     * Document XML
     *
     * @var string
     */
    private $documentXML;

    /**
     * Document header XML
     *
     * @var string[]
     */
    private $headerXMLs = array();

    /**
     * Document footer XML
     *
     * @var string[]
     */
    private $footerXMLs = array();

    /**
     * Create a new Template Object
     *
     * @param string $strFilename
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function __construct($strFilename)
    {
        $this->tempFileName = tempnam(sys_get_temp_dir(), '');
        if ($this->tempFileName === false) {
            throw new Exception('Could not create temporary file with unique name in the default temporary directory.');
        }

        // Copy the source File to the temp File
        if (!copy($strFilename, $this->tempFileName)) {
            throw new Exception("Could not copy the template from {$strFilename} to {$this->tempFileName}.");
        }

        $this->zipClass = new ZipArchive();
        $this->zipClass->open($this->tempFileName);

        // Find and load headers and footers
        $index = 1;
        while ($this->zipClass->locateName($this->getHeaderName($index)) !== false) {
            $this->headerXMLs[$index] = $this->zipClass->getFromName($this->getHeaderName($index));
            $index++;
        }

        $index = 1;
        while ($this->zipClass->locateName($this->getFooterName($index)) !== false) {
            $this->footerXMLs[$index] = $this->zipClass->getFromName($this->getFooterName($index));
            $index++;
        }

        $this->documentXML = $this->zipClass->getFromName('word/document.xml');
    }

    /**
     * Applies XSL style sheet to template's parts
     *
     * @param \DOMDocument $xslDOMDocument
     * @param array $xslOptions
     * @param string $xslOptionsURI
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function applyXslStyleSheet(&$xslDOMDocument, $xslOptions = array(), $xslOptionsURI = '')
    {
        $processor = new \XSLTProcessor();

        $processor->importStylesheet($xslDOMDocument);

        if ($processor->setParameter($xslOptionsURI, $xslOptions) === false) {
            throw new Exception('Could not set values for the given XSL style sheet parameters.');
        }

        $xmlDOMDocument = new \DOMDocument();
        if ($xmlDOMDocument->loadXML($this->documentXML) === false) {
            throw new Exception('Could not load XML from the given template.');
        }

        $xmlTransformed = $processor->transformToXml($xmlDOMDocument);
        if ($xmlTransformed === false) {
            throw new Exception('Could not transform the given XML document.');
        }

        $this->documentXML = $xmlTransformed;
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
        foreach ($this->headerXMLs as $index => $headerXML) {
            $this->headerXMLs[$index] = $this->setValueForPart($this->headerXMLs[$index], $search, $replace, $limit);
        }

        $this->documentXML = $this->setValueForPart($this->documentXML, $search, $replace, $limit);

        foreach ($this->footerXMLs as $index => $headerXML) {
            $this->footerXMLs[$index] = $this->setValueForPart($this->footerXMLs[$index], $search, $replace, $limit);
        }
    }

    /**
     * Returns array of all variables in template
     * @return string[]
     */
    public function getVariables()
    {
        $variables = $this->getVariablesForPart($this->documentXML);

        foreach ($this->headerXMLs as $headerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($headerXML));
        }

        foreach ($this->footerXMLs as $footerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($footerXML));
        }

        return array_unique($variables);
    }

    /**
     * Clone a table row in a template document
     *
     * @param string $search
     * @param integer $numberOfClones
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function cloneRow($search, $numberOfClones)
    {
        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${' . $search . '}';
        }

        $tagPos = strpos($this->documentXML, $search);
        if (!$tagPos) {
            throw new Exception("Can not clone row, template variable not found or variable contains markup.");
        }

        $rowStart = $this->findRowStart($tagPos);
        $rowEnd = $this->findRowEnd($tagPos);
        $xmlRow = $this->getSlice($rowStart, $rowEnd);

        // Check if there's a cell spanning multiple rows.
        if (preg_match('#<w:vMerge w:val="restart"/>#', $xmlRow)) {
            // $extraRowStart = $rowEnd;
            $extraRowEnd = $rowEnd;
            while (true) {
                $extraRowStart = $this->findRowStart($extraRowEnd + 1);
                $extraRowEnd = $this->findRowEnd($extraRowEnd + 1);

                // If extraRowEnd is lower then 7, there was no next row found.
                if ($extraRowEnd < 7) {
                    break;
                }

                // If tmpXmlRow doesn't contain continue, this row is no longer part of the spanned row.
                $tmpXmlRow = $this->getSlice($extraRowStart, $extraRowEnd);
                if (!preg_match('#<w:vMerge/>#', $tmpXmlRow) &&
                    !preg_match('#<w:vMerge w:val="continue" />#', $tmpXmlRow)) {
                    break;
                }
                // This row was a spanned row, update $rowEnd and search for the next row.
                $rowEnd = $extraRowEnd;
            }
            $xmlRow = $this->getSlice($rowStart, $rowEnd);
        }

        $result = $this->getSlice(0, $rowStart);
        for ($i = 1; $i <= $numberOfClones; $i++) {
            $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlRow);
        }
        $result .= $this->getSlice($rowEnd);

        $this->documentXML = $result;
    }

    /**
     * Clone a block
     *
     * @param string $blockname
     * @param integer $clones
     * @param boolean $replace
     * @return string|null
     */
    public function cloneBlock($blockname, $clones = 1, $replace = true)
    {
        $xmlBlock = null;
        preg_match(
            '/(<\?xml.*)(<w:p.*>\${' . $blockname . '}<\/w:.*?p>)(.*)(<w:p.*\${\/' . $blockname . '}<\/w:.*?p>)/is',
            $this->documentXML,
            $matches
        );

        if (isset($matches[3])) {
            $xmlBlock = $matches[3];
            $cloned = array();
            for ($i = 1; $i <= $clones; $i++) {
                $cloned[] = $xmlBlock;
            }

            if ($replace) {
                $this->documentXML = str_replace(
                    $matches[2] . $matches[3] . $matches[4],
                    implode('', $cloned),
                    $this->documentXML
                );
            }
        }

        return $xmlBlock;
    }

    /**
     * Replace a block
     *
     * @param string $blockname
     * @param string $replacement
     */
    public function replaceBlock($blockname, $replacement)
    {
        preg_match(
            '/(<\?xml.*)(<w:p.*>\${' . $blockname . '}<\/w:.*?p>)(.*)(<w:p.*\${\/' . $blockname . '}<\/w:.*?p>)/is',
            $this->documentXML,
            $matches
        );

        if (isset($matches[3])) {
            $this->documentXML = str_replace(
                $matches[2] . $matches[3] . $matches[4],
                $replacement,
                $this->documentXML
            );
        }
    }

    /**
     * Delete a block of text
     *
     * @param string $blockname
     */
    public function deleteBlock($blockname)
    {
        $this->replaceBlock($blockname, '');
    }

    /**
     * Save XML to temporary file
     *
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function save()
    {
        foreach ($this->headerXMLs as $index => $headerXML) {
            $this->zipClass->addFromString($this->getHeaderName($index), $this->headerXMLs[$index]);
        }

        $this->zipClass->addFromString('word/document.xml', $this->documentXML);

        foreach ($this->footerXMLs as $index => $headerXML) {
            $this->zipClass->addFromString($this->getFooterName($index), $this->footerXMLs[$index]);
        }

        // Close zip file
        if ($this->zipClass->close() === false) {
            throw new Exception('Could not close zip file.');
        }

        return $this->tempFileName;
    }

    /**
     * Save XML to defined name
     *
     * @param string $strFilename
     * @since 0.8.0
     */
    public function saveAs($strFilename)
    {
        $tempFilename = $this->save();

        if (file_exists($strFilename)) {
            unlink($strFilename);
        }

        rename($tempFilename, $strFilename);
    }

    /**
     * Find and replace placeholders in the given XML section.
     *
     * @param string $documentPartXML
     * @param string $search
     * @param string $replace
     * @param integer $limit
     * @return string
     */
    protected function setValueForPart($documentPartXML, $search, $replace, $limit)
    {
        $pattern = '|\$\{([^\}]+)\}|U';
        preg_match_all($pattern, $documentPartXML, $matches);
        foreach ($matches[0] as $value) {
            $valueCleaned = preg_replace('/<[^>]+>/', '', $value);
            $valueCleaned = preg_replace('/<\/[^>]+>/', '', $valueCleaned);
            $documentPartXML = str_replace($value, $valueCleaned, $documentPartXML);
        }

        if (substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${' . $search . '}';
        }

        if (!String::isUTF8($replace)) {
            $replace = utf8_encode($replace);
        }
        $replace = htmlspecialchars($replace);

        $regExpDelim = '/';
        $escapedSearch = preg_quote($search, $regExpDelim);
        return preg_replace("{$regExpDelim}{$escapedSearch}{$regExpDelim}u", $replace, $documentPartXML, $limit);
    }

    /**
     * Find all variables in $documentPartXML
     * @param string $documentPartXML
     * @return string[]
     */
    protected function getVariablesForPart($documentPartXML)
    {
        preg_match_all('/\$\{(.*?)}/i', $documentPartXML, $matches);

        return $matches[1];
    }

    /**
     * Get the name of the footer file for $index
     * @param integer $index
     * @return string
     */
    private function getFooterName($index)
    {
        return sprintf('word/footer%d.xml', $index);
    }

    /**
     * Get the name of the header file for $index
     * @param integer $index
     * @return string
     */
    private function getHeaderName($index)
    {
        return sprintf('word/header%d.xml', $index);
    }

    /**
     * Find the start position of the nearest table row before $offset
     *
     * @param integer $offset
     * @return integer
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private function findRowStart($offset)
    {
        $rowStart = strrpos($this->documentXML, "<w:tr ", ((strlen($this->documentXML) - $offset) * -1));
        if (!$rowStart) {
            $rowStart = strrpos($this->documentXML, "<w:tr>", ((strlen($this->documentXML) - $offset) * -1));
        }
        if (!$rowStart) {
            throw new Exception("Can not find the start position of the row to clone.");
        }
        return $rowStart;
    }

    /**
     * Find the end position of the nearest table row after $offset
     *
     * @param integer $offset
     * @return integer
     */
    private function findRowEnd($offset)
    {
        $rowEnd = strpos($this->documentXML, "</w:tr>", $offset) + 7;
        return $rowEnd;
    }

    /**
     * Get a slice of a string
     *
     * @param integer $startPosition
     * @param integer $endPosition
     * @return string
     */
    private function getSlice($startPosition, $endPosition = 0)
    {
        if (!$endPosition) {
            $endPosition = strlen($this->documentXML);
        }
        return substr($this->documentXML, $startPosition, ($endPosition - $startPosition));
    }
}
