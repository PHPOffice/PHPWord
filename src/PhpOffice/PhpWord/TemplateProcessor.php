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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use DOMDocument;
use DOMElement;
use DOMXPath;
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\ZipArchive;

class TemplateProcessor
{
    const MAXIMUM_REPLACEMENTS_DEFAULT = -1;

    /**
     * @var string
     */
    protected $tempDocumentFilename;

    /**
     * @var string
     */
    protected $tempDocumentMainDocument;

    /**
     * @var array
     */
    protected $tempDocumentHeaders = [];

    /**
     * @var array
     */
    protected $tempDocumentFooters = [];

    /**
     * @var ZipArchive
     */
    protected $zip;

    /**
     * @param string $documentTemplate
     */
    public function __construct($documentTemplate)
    {
        $this->tempDocumentFilename = tempnam(Settings::getTempDir(), 'PhpWord');
        if (false === $this->tempDocumentFilename || !copy($documentTemplate, $this->tempDocumentFilename)) {
            throw new CopyFileException($documentTemplate, $this->tempDocumentFilename);
        }

        $this->zip = new ZipArchive();
        $this->zip->open($this->tempDocumentFilename);

        $this->tempDocumentMainDocument = $this->zip->getFromName($this->getMainPartName());

        $this->readHeaders();
        $this->readFooters();
    }

    /**
     * @return string
     */
    protected function getMainPartName()
    {
        return 'word/document.xml';
    }

    /**
     * @return ZipArchive
     */
    public function zip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getTempDocumentFilename()
    {
        return $this->tempDocumentFilename;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        $variables = [];

        $this->findVariables($this->tempDocumentMainDocument, $variables);

        foreach ($this->tempDocumentHeaders as $header) {
            $this->findVariables($header['content'], $variables);
        }

        foreach ($this->tempDocumentFooters as $footer) {
            $this->findVariables($footer['content'], $variables);
        }

        return array_keys($variables);
    }

    /**
     * Check if a macro exists.
     *
     * @param string $macro
     *
     * @return bool
     */
    public function macroExists($macro)
    {
        return $this->findMacro($macro) !== -1;
    }

    /**
     * @param string $search
     * @param string $replace
     * @param int $limit
     */
    public function setValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        $this->tempDocumentMainDocument = self::setValueForPart($search, $replace, $this->tempDocumentMainDocument, $limit);

        foreach ($this->tempDocumentHeaders as $index => $header) {
            $this->tempDocumentHeaders[$index]['content'] = self::setValueForPart($search, $replace, $header['content'], $limit);
        }

        foreach ($this->tempDocumentFooters as $index => $footer) {
            $this->tempDocumentFooters[$index]['content'] = self::setValueForPart($search, $replace, $footer['content'], $limit);
        }
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        foreach ($values as $search => $replace) {
            $this->setValue($search, $replace);
        }
    }

    /**
     * @param string $search
     * @param AbstractElement $complexType
     */
    public function setComplexValue($search, AbstractElement $complexType)
    {
        $elementAsXml = $this->getRenderedPart($complexType);

        $this->tempDocumentMainDocument = self::setValueForPart($search, $elementAsXml, $this->tempDocumentMainDocument, self::MAXIMUM_REPLACEMENTS_DEFAULT);

        foreach ($this->tempDocumentHeaders as $index => $header) {
            $this->tempDocumentHeaders[$index]['content'] = self::setValueForPart($search, $elementAsXml, $header['content'], self::MAXIMUM_REPLACEMENTS_DEFAULT);
        }

        foreach ($this->tempDocumentFooters as $index => $footer) {
            $this->tempDocumentFooters[$index]['content'] = self::setValueForPart($search, $elementAsXml, $footer['content'], self::MAXIMUM_REPLACEMENTS_DEFAULT);
        }
    }

    /**
     * @param string $search
     * @param AbstractElement $complexType
     */
    public function setComplexBlock($search, AbstractElement $complexType)
    {
        $elementAsXml = $this->getRenderedPart($complexType);

        $this->replaceBlock($search, $elementAsXml);
    }

    /**
     * @param string $search
     * @param string $replace
     */
    public function setImageValue($search, $replace)
    {
        // Replace search string with image XML
    }

    /**
     * @param string $search
     * @param int $count
     */
    public function cloneRow($search, $count)
    {
        $macro = self::ensureMacroName($search);

        $xmlBlock = $this->tempDocumentMainDocument;
        if (($pos = strpos($xmlBlock, $macro)) !== false) {
            // Logic for cloning row
        }
    }

    /**
     * @param string $blockname
     * @param int $clones
     * @param bool $replace
     */
    public function cloneBlock($blockname, $clones = 1, $replace = true)
    {
        // Logic for cloning block
    }

    /**
     * @param string $blockname
     * @param string $replacement
     */
    public function replaceBlock($blockname, $replacement)
    {
        // Logic for replacing block
    }

    /**
     * @param string $blockname
     */
    public function deleteBlock($blockname)
    {
        $this->replaceBlock($blockname, '');
    }

    /**
     * @param string $search
     * @param int $count
     */
    public function cloneMainDocumentPart($search, $count)
    {
        // Logic for cloning main document part
    }

    /**
     * Saves the result document.
     *
     * @return string
     */
    public function save()
    {
        foreach ($this->tempDocumentHeaders as $index => $header) {
            $this->zip->addFromString($header['name'], $header['content']);
        }

        foreach ($this->tempDocumentFooters as $index => $footer) {
            $this->zip->addFromString($footer['name'], $footer['content']);
        }

        $this->zip->addFromString($this->getMainPartName(), $this->tempDocumentMainDocument);

        $this->zip->close();

        return $this->tempDocumentFilename;
    }

    /**
     * Saves the result document to the given filename.
     *
     * @param string $filename
     */
    public function saveAs($filename)
    {
        $tempFilename = $this->save();

        if (file_exists($filename)) {
            unlink($filename);
        }

        copy($tempFilename, $filename);
    }

    /**
     * Finds macro in template main document.
     *
     * @param string $macro
     * @param int $offset
     *
     * @return int
     */
    protected function findMacro($macro, $offset = 0)
    {
        $macro = self::ensureMacroName($macro);

        $pos = strpos($this->tempDocumentMainDocument, $macro, $offset);

        return ($pos !== false) ? $pos : -1;
    }

    /**
     * Ensures that the macro string is enclosed with ${}.
     *
     * @param string $macro
     *
     * @return string
     */
    protected static function ensureMacroName($macro)
    {
        if (strpos($macro, '${') !== 0) {
            $macro = '${' . $macro . '}';
        }

        return $macro;
    }

    /**
     * @param string $search
     * @param string $replace
     * @param string $documentPartXML
     * @param int $limit
     *
     * @return string
     */
    protected static function setValueForPart($search, $replace, $documentPartXML, $limit)
    {
        // Value setting helper
        return $documentPartXML;
    }

    /**
     * Read headers from zip.
     */
    protected function readHeaders()
    {
        // Read headers
    }

    /**
     * Read footers from zip.
     */
    protected function readFooters()
    {
        // Read footers
    }

    /**
     * Find variables in XML string.
     *
     * @param string $xml
     * @param array $variables
     */
    protected function findVariables($xml, &$variables)
    {
        preg_match_all('/\$\{([^\}]+)\}/U', $xml, $matches);

        foreach ($matches[1] as $variable) {
            $variables[$variable] = true;
        }
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function getRenderedPart(AbstractElement $element)
    {
        return '';
    }

    /**
     * @param DOMDocument $xslDomDocument
     * @param array $xslParameters
     */
    public function applyXslStyleSheet(DOMDocument $xslDomDocument, array $xslParameters = [])
    {
        // Apply XSL stylesheet
    }
}
