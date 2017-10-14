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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Escaper\RegExp;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Zend\Stdlib\StringUtils;

class TemplateProcessor
{
    const MAXIMUM_REPLACEMENTS_DEFAULT = -1;

    public static $ensureMacroCompletion = true;

    /**
     * ZipArchive object.
     *
     * @var mixed
     */
    protected $zipClass;

    /**
     * @var string Temporary document filename (with path).
     */
    protected $tempDocumentFilename;

    /**
     * Content of main document part (in XML format) of the temporary document.
     *
     * @var string
     */
    protected $tempDocumentMainPart;

    /**
     * Content of headers (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentHeaders = array();

    /**
     * Content of footers (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentFooters = array();

    /**
     * @since 0.12.0 Throws CreateTemporaryFileException and CopyFileException instead of Exception.
     *
     * @param string $documentTemplate The fully qualified template filename.
     *
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     */
    public function __construct($documentTemplate)
    {
        // Temporary document filename initialization
        $this->tempDocumentFilename = tempnam(Settings::getTempDir(), 'PhpWord');
        if (false === $this->tempDocumentFilename) {
            throw new CreateTemporaryFileException();
        }

        // Template file cloning
        if (false === copy($documentTemplate, $this->tempDocumentFilename)) {
            throw new CopyFileException($documentTemplate, $this->tempDocumentFilename);
        }

        // Temporary document content extraction
        $this->zipClass = new ZipArchive();
        $this->zipClass->open($this->tempDocumentFilename);
        $index = 1;
        while (false !== $this->zipClass->locateName($this->getHeaderName($index))) {
            $this->tempDocumentHeaders[$index] = $this->fixBrokenMacros(
                $this->zipClass->getFromName($this->getHeaderName($index))
            );
            $index++;
        }
        $index = 1;
        while (false !== $this->zipClass->locateName($this->getFooterName($index))) {
            $this->tempDocumentFooters[$index] = $this->fixBrokenMacros(
                $this->zipClass->getFromName($this->getFooterName($index))
            );
            $index++;
        }
        $this->tempDocumentMainPart = $this->fixBrokenMacros($this->zipClass->getFromName($this->getMainPartName()));
    }

    /**
     * @param string $xml
     * @param \XSLTProcessor $xsltProcessor
     *
     * @return string
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function transformSingleXml($xml, $xsltProcessor)
    {
        $domDocument = new \DOMDocument();
        if (false === $domDocument->loadXML($xml)) {
            throw new Exception('Could not load the given XML document.');
        }

        $transformedXml = $xsltProcessor->transformToXml($domDocument);
        if (false === $transformedXml) {
            throw new Exception('Could not transform the given XML document.');
        }

        return $transformedXml;
    }

    /**
     * @param mixed $xml
     * @param \XSLTProcessor $xsltProcessor
     *
     * @return mixed
     */
    protected function transformXml($xml, $xsltProcessor)
    {
        if (is_array($xml)) {
            foreach ($xml as &$item) {
                $item = $this->transformSingleXml($item, $xsltProcessor);
            }
            return (array)$xml;
        } else {
            $xml = $this->transformSingleXml($xml, $xsltProcessor);
            return (string)$xml;
        }
    }

    /**
     * Applies XSL style sheet to template's parts.
     *
     * Note: since the method doesn't make any guess on logic of the provided XSL style sheet,
     * make sure that output is correctly escaped. Otherwise you may get broken document.
     *
     * @param \DOMDocument $xslDomDocument
     * @param array $xslOptions
     * @param string $xslOptionsUri
     *
     * @return void
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function applyXslStyleSheet($xslDomDocument, $xslOptions = array(), $xslOptionsUri = '')
    {
        $xsltProcessor = new \XSLTProcessor();

        $xsltProcessor->importStylesheet($xslDomDocument);
        if (false === $xsltProcessor->setParameter($xslOptionsUri, $xslOptions)) {
            throw new Exception('Could not set values for the given XSL style sheet parameters.');
        }

        $this->tempDocumentHeaders = (array)$this->transformXml($this->tempDocumentHeaders, $xsltProcessor);
        $this->tempDocumentMainPart = (string)$this->transformXml($this->tempDocumentMainPart, $xsltProcessor);
        $this->tempDocumentFooters = (array)$this->transformXml($this->tempDocumentFooters, $xsltProcessor);
    }

    /**
     * @param string $macro
     *
     * @return string
     */
    protected static function ensureMacroCompleted($macro)
    {
        if (TemplateProcessor::$ensureMacroCompletion && substr($macro, 0, 2) !== '${' && substr($macro, -1) !== '}') {
            $macro = '${' . $macro . '}';
        }

        return $macro;
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    protected static function ensureUtf8Encoded($subject)
    {
        if (!StringUtils::isValidUtf8($subject)) {
            $subject = utf8_encode($subject);
        }

        return $subject;
    }

    /**
     * @param mixed $search
     * @param mixed $replace
     * @param integer $limit
     *
     * @return void
     */
    public function setValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (is_array($search)) {
            foreach ($search as &$item) {
                $item = self::ensureMacroCompleted($item);
            }
        } else {
            $search = self::ensureMacroCompleted($search);
        }

        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = self::ensureUtf8Encoded($item);
            }
        } else {
            $replace = self::ensureUtf8Encoded($replace);
        }

        if (Settings::isOutputEscapingEnabled()) {
            $xmlEscaper = new Xml();
            $replace = $xmlEscaper->escape($replace);
        }

        $this->tempDocumentMainPart = $this->setValueForPart($search, $replace, $this->tempDocumentMainPart, $limit);

        $documentParts = array('tempDocumentHeaders', 'tempDocumentFooters');
        foreach ($documentParts as $tempDocumentParts) {
            foreach ($this->{$tempDocumentParts} as &$tempDocumentPart) {
                $tempDocumentPart = $this->setValueForPart($search, $replace, $tempDocumentPart, $limit);
            }
        }
    }

    /**
     * Replaces a closed block with text
     *
     * @param string  $blockname Your macro must end with slash, i.e.: ${value/}
     * @param mixed   $replace Array or the text can be multiline (contain \n). It will cloneBlock().
     * @param integer $limit
     *
     * @return void
     */
    public function setBlock($blockname, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (preg_match('~\R~u', $replace)) {
            $replace = preg_split('~\R~u', $replace);
        }
        if (is_array($replace)) {
            $this->cloneBlock($blockname, count($replace), true, false);
            foreach ($replace as $oneVal) {
                $this->setValue($blockname, $oneVal, 1);
            }
        } else {
            $this->setValue($blockname, $replace, $limit);
        }
    }

    /**
     * Updates a file inside the document, from a string (with binary data)
     *
     * @param string $localname
     * @param string $contents
     *
     * @return bool
     */
    public function zipAddFromString($localname, $contents)
    {
        return $this->zipClass->AddFromString($localname, $contents);
    }

    /**
     * If $throwException is true, it throws an exception, else it returns $elseReturn
     *
     * @param string $exceptionText
     * @param bool   $throwException
     * @param mixed $elseReturn
     *
     * @return mixed
     */
    private function failGraciously($exceptionText, $throwException, $elseReturn)
    {
        if ($throwException) {
            throw new Exception($exceptionText);
        } else {
            return $elseReturn;
        }
    }

    /**
     * Returns array of all variables in template.
     *
     * @return string[]
     */
    public function getVariables()
    {
        $variables = $this->getVariablesForPart($this->tempDocumentMainPart);

        foreach ($this->tempDocumentHeaders as $headerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($headerXML));
        }

        foreach ($this->tempDocumentFooters as $footerXML) {
            $variables = array_merge($variables, $this->getVariablesForPart($footerXML));
        }

        return array_unique($variables);
    }

    /**
     * Clone a table row in a template document.
     *
     * @param string  $search
     * @param integer $numberOfClones
     * @param bool    $replace
     * @param bool    $incrementVariables
     * @param bool    $throwException
     *
     * @return string|null
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function cloneRow(
        $search,
        $numberOfClones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        $tagPos = strpos($this->tempDocumentMainPart, $this->ensureMacroCompleted($search));
        if (!$tagPos) {
            return $this->failGraciously(
                "Can not clone row, template variable not found or variable contains markup.",
                $throwException,
                null
            );
        }

        $rowStart = $this->findTagLeft($this->tempDocumentMainPart, '<w:tr>', $tagPos, $throwException);
        $rowEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:tr>', $tagPos);
        $xmlRow = $this->getSlice($this->tempDocumentMainPart, $rowStart, $rowEnd);

        // Check if there's a cell spanning multiple rows.
        if (preg_match('#<w:vMerge w:val="restart"/>#', $xmlRow)) {
            // $extraRowStart = $rowEnd;
            $extraRowEnd = $rowEnd;
            while (true) {
                $extraRowStart = $this->findTagLeft(
                    $this->tempDocumentMainPart,
                    '<w:tr>',
                    $extraRowEnd + 1,
                    $throwException
                );
                $extraRowEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:tr>', $extraRowEnd + 1);

                if (!$extraRowEnd) {
                    break;
                }

                // If tmpXmlRow doesn't contain continue, this row is no longer part of the spanned row.
                $tmpXmlRow = $this->getSlice($this->tempDocumentMainPart, $extraRowStart, $extraRowEnd);
                if (!preg_match('#<w:vMerge/>#', $tmpXmlRow)
                    && !preg_match('#<w:vMerge w:val="continue" />#', $tmpXmlRow)
                ) {
                    break;
                }
                // This row was a spanned row, update $rowEnd and search for the next row.
                $rowEnd = $extraRowEnd;
            }
            $xmlRow = $this->getSlice($this->tempDocumentMainPart, $rowStart, $rowEnd);
        }

        if ($replace) {
            $result = $this->getSlice($this->tempDocumentMainPart, 0, $rowStart);
            for ($i = 1; $i <= $numberOfClones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlRow);
                } else {
                    $result .= $xmlRow;
                }
            }
            $result .= $this->getSlice($this->tempDocumentMainPart, $rowEnd);

            $this->tempDocumentMainPart = $result;
        }

        return $xmlRow;
    }

    /**
     * Delete a row containing the given variable
     *
     * @param string $search
     *
     * @return \PhpOffice\PhpWord\Template
     */
    public function deleteRow($search)
    {
        return $this->deleteSegment($this->ensureMacroCompleted($search), 'w:tr');
    }

    /**
     * Clone a block.
     *
     * @param string  $blockname
     * @param integer $clones
     * @param boolean $replace
     * @param boolean $incrementVariables
     * @param boolean $throwException
     *
     * @return string|null
     */
    public function cloneBlock(
        $blockname,
        $clones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        $startSearch = '${'  . $blockname . '}';
        $endSearch =   '${/' . $blockname . '}';

        if (substr($blockname, -1) == '/') { // singleton/closed block
            return $this->cloneSegment(
                $startSearch,
                'w:p',
                'MainPart',
                $clones,
                $replace,
                $incrementVariables,
                $throwException
            );
        }

        $startTagPos = strpos($this->tempDocumentMainPart, $startSearch);
        $endTagPos = strpos($this->tempDocumentMainPart, $endSearch, $startTagPos);

        if (!$startTagPos || !$endTagPos) {
            return $this->failGraciously(
                "Can not find block '$blockname', template variable not found or variable contains markup.",
                $throwException,
                null
            );
        }

        $startBlockStart = $this->findTagLeft($this->tempDocumentMainPart, '<w:p>', $startTagPos, $throwException);
        $startBlockEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:p>', $startTagPos);

        if (!$startBlockStart || !$startBlockEnd) {
            return $this->failGraciously(
                "Can not find start paragraph around block '$blockname'",
                $throwException,
                false
            );
        }

        $endBlockStart = $this->findTagLeft($this->tempDocumentMainPart, '<w:p>', $endTagPos, $throwException);
        $endBlockEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:p>', $endTagPos);

        if (!$endBlockStart || !$endBlockEnd) {
            return $this->failGraciously(
                "Can not find end paragraph around block '$blockname'",
                $throwException,
                false
            );
        }

        if ($startBlockEnd == $endBlockEnd) { // inline block
            $startBlockStart = $startTagPos;
            $startBlockEnd = $startTagPos + strlen($startSearch);
            $endBlockStart = $endTagPos;
            $endBlockEnd = $endTagPos + strlen($endSearch);
        }

        $xmlBlock = $this->getSlice($this->tempDocumentMainPart, $startBlockEnd, $endBlockStart);

        if ($replace) {
            $result = $this->getSlice($this->tempDocumentMainPart, 0, $startBlockStart);
            for ($i = 1; $i <= $clones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlBlock);
                } else {
                    $result .= $xmlBlock;
                }
            }
            $result .= $this->getSlice($this->tempDocumentMainPart, $endBlockEnd);

            $this->tempDocumentMainPart = $result;
        }

        return $xmlBlock;
    }

    /**
     * Clone a segment.
     *
     * @param string  $needle  If this is a macro, you need to add the ${} around it yourself.
     * @param string  $xmltag  an xml tag without brackets, for example:  w:p
     * @param string  $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (first header)
     * @param integer $clones  How many times the segment needs to be cloned
     * @param boolean $replace true by default (if you use false, cloneSegment behaves like getSegment())
     * @param boolean $incrementVariables true by default (variables get appended #1, #2 inside the cloned blocks)
     * @param boolean $throwException false by default (it then returns false or null on errors).
     *
     * @return string|false|null Returns the cloned segment, or false if $needle can not be found or null for no tags
     */
    public function cloneSegment(
        $needle,
        $xmltag,
        $docPart = 'MainPart',
        $clones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwException = false
    ) {
        $docPart = preg_split('/:/', $docPart);
        if (count($docPart)>1) {
            $part = &$this->{"tempDocument".$docPart[0]}[$docPart[1]];
        } else {
            $part = &$this->{"tempDocument".$docPart[0]};
        }
        $needlePos = strpos($part, $needle);

        if ($needlePos === false) {
            return $this->failGraciously(
                "Can not find segment '$needle', text not found or text contains markup.",
                $throwException,
                false
            );
        }

        $segmentStart = $this->findTagLeft($part, "<$xmltag>", $needlePos, $throwException);
        $segmentEnd = $this->findTagRight($part, "</$xmltag>", $needlePos);

        if (!$segmentStart || !$segmentEnd) {
            return $this->failGraciously(
                "Can not find <$xmltag> around segment '$needle'",
                $throwException,
                null
            );
        }

        $xmlSegment = $this->getSlice($part, $segmentStart, $segmentEnd);

        if ($replace) {
            $result = $this->getSlice($part, 0, $segmentStart);
            for ($i = 1; $i <= $clones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlSegment);
                } else {
                    $result .= $xmlSegment;
                }
            }
            $result .= $this->getSlice($part, $segmentEnd);

            $part = $result;
        }

        return $xmlSegment;
    }

    /**
     * Get a block. (first block found)
     *
     * @param string  $blockname
     * @param boolean $throwException
     *
     * @return string|null
     */
    public function getBlock($blockname, $throwException = false)
    {
        return $this->cloneBlock($blockname, 1, false, false, $throwException);
    }

    /**
     * Get a segment. (first segment found)
     *
     * @param string  $needle If this is a macro, you need to add the ${} around it yourself.
     * @param string  $xmltag an xml tag without brackets, for example:  w:p
     * @param string  $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (first header)
     * @param boolean $throwException false by default (it then returns false or null on errors).
     *
     * @return string|null
     */
    public function getSegment($needle, $xmltag, $docPart = 'MainPart', $throwException = false)
    {
        return $this->cloneSegment($needle, $xmltag, $docPart, 1, false, false, $throwException);
    }

    /**
     * Get a row. (first block found)
     *
     * @param string  $rowname
     * @param boolean $throwException
     *
     * @return string|null
     */
    public function getRow($rowname, $throwException = false)
    {
        return $this->cloneRow($rowname, 1, false, false, $throwException);
    }

    /**
     * Replace a block.
     *
     * @param string  $blockname
     * @param string  $replacement
     * @param boolean $throwException
     *
     * @return false on no replacement, true on replacement
     */
    public function replaceBlock($blockname, $replacement = '', $throwException = false)
    {
        $startSearch = '${'  . $blockname . '}';
        $endSearch   = '${/' . $blockname . '}';

        if (substr($blockname, -1) == '/') { // singleton/closed block
            return $this->replaceSegment($startSearch, 'w:p', $replacement, 'MainPart', $throwException);
        }

        $startTagPos = strpos($this->tempDocumentMainPart, $startSearch);
        $endTagPos = strpos($this->tempDocumentMainPart, $endSearch, $startTagPos);

        if (!$startTagPos || !$endTagPos) {
            return $this->failGraciously(
                "Can not find block '$blockname', template variable not found or variable contains markup.",
                $throwException,
                false
            );
        }

        $startBlockStart = $this->findTagLeft($this->tempDocumentMainPart, '<w:p>', $startTagPos, $throwException);
        $endBlockEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:p>', $endTagPos);

        if (!$startBlockStart || !$endBlockEnd) {
            return $this->failGraciously(
                "Can not find end paragraph around block '$blockname'",
                $throwException,
                false
            );
        }

        $startBlockEnd = $this->findTagRight($this->tempDocumentMainPart, '</w:p>', $startTagPos);
        if ($startBlockEnd == $endBlockEnd) { // inline block
            $startBlockStart = $startTagPos;
            $endBlockEnd = $endTagPos + strlen($endSearch);
        }

        $this->tempDocumentMainPart =
            $this->getSlice($this->tempDocumentMainPart, 0, $startBlockStart)
            . $replacement
            . $this->getSlice($this->tempDocumentMainPart, $endBlockEnd);

        return true;
    }

    /**
     * Replace a segment.
     *
     * @param string  $needle If this is a macro, you need to add the ${} around it yourself.
     * @param string  $xmltag an xml tag without brackets, for example:  w:p
     * @param string  $replacement The replacement xml string. Be careful and keep the xml uncorrupted.
     * @param string  $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:2' (second header)
     * @param boolean $throwException false by default (it then returns false or null on errors).
     *
     * @return true on replacement, false if $needle can not be found or null if no tags can be found around $needle
     */
    public function replaceSegment($needle, $xmltag, $replacement = '', $docPart = 'MainPart', $throwException = false)
    {
        $docPart = preg_split('/:/', $docPart);
        if (count($docPart)>1) {
            $part = &$this->{"tempDocument".$docPart[0]}[$docPart[1]];
        } else {
            $part = &$this->{"tempDocument".$docPart[0]};
        }
        $needlePos = strpos($part, $needle);

        if ($needlePos === false) {
            return $this->failGraciously(
                "Can not find segment '$needle', text not found or text contains markup.",
                $throwException,
                false
            );
        }

        $segmentStart = $this->findTagLeft($part, "<$xmltag>", $needlePos, $throwException);
        $segmentEnd = $this->findTagRight($part, "</$xmltag>", $needlePos);

        if (!$segmentStart || !$segmentEnd) {
            return $this->failGraciously(
                "Can not find tag $xmltag around segment '$needle'.",
                $throwException,
                null
            );
        }

        $part =
            $this->getSlice($part, 0, $segmentStart)
            . $replacement
            . $this->getSlice($part, $segmentEnd);

        return true;
    }

    /**
     * Delete a block of text.
     *
     * @param string $blockname
     *
     * @return true on block found and deleted, false on block not found.
     */
    public function deleteBlock($blockname)
    {
        return $this->replaceBlock($blockname, '', false);
    }

    /**
     * Delete a segment of text.
     *
     * @param string $needle If this is a macro, you need to add the ${} yourself.
     * @param string $xmltag an xml tag without brackets, for example:  w:p
     * @param string $docPart 'MainPart' (default) 'Footers:1' (first footer) or 'Headers:1' (second header)
     *
     * @return true on segment found and deleted, false on segment not found.
     */
    public function deleteSegment($needle, $xmltag, $docPart = 'MainPart')
    {
        return $this->replaceSegment($needle, $xmltag, '', $docPart, false);
    }

    /**
     * Saves the result document.
     *
     * @return string The filename of the document
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function save()
    {
        foreach ($this->tempDocumentHeaders as $index => $xml) {
            $this->zipClass->addFromString($this->getHeaderName($index), $xml);
        }

        $this->zipClass->addFromString($this->getMainPartName(), $this->tempDocumentMainPart);

        foreach ($this->tempDocumentFooters as $index => $xml) {
            $this->zipClass->addFromString($this->getFooterName($index), $xml);
        }

        // Close zip file
        if (false === $this->zipClass->close()) {
            throw new Exception('Could not close zip file.');
        }

        return $this->tempDocumentFilename;
    }

    /**
     * Saves the result document to the user defined file.
     *
     * @since 0.8.0
     *
     * @param string $fileName
     *
     * @return void
     */
    public function saveAs($fileName)
    {
        $tempFileName = $this->save();

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        /*
         * Note: we do not use `rename` function here, because it looses file ownership data on Windows platform.
         * As a result, user cannot open the file directly getting "Access denied" message.
         *
         * @see https://github.com/PHPOffice/PHPWord/issues/532
         */
        copy($tempFileName, $fileName);
        unlink($tempFileName);
    }

    /**
     * Finds parts of broken macros and sticks them together.
     * Macros, while being edited, could be implicitly broken by some of the word processors.
     * In order to limit side-effects, we limit matches to only inside (inner) paragraphs
     *
     * @param string $documentPart The document part in XML representation.
     *
     * @return string
     */
    protected function fixBrokenMacros($documentPart)
    {
        $paragraphs = preg_split(
            '@(</?w:p\b[^>]*>)@',
            $documentPart,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        foreach ($paragraphs as &$paragraph) {
            $paragraph = preg_replace_callback(
                '|\$(?:<[^{}]*)?\{[^{}]*\}|U',
                function ($match) {
                    return strip_tags($match[0]);
                },
                $paragraph
            );
        }
        return implode('', $paragraphs);
    }

    /**
     * Find and replace macros in the given XML section.
     *
     * @param mixed   $search
     * @param mixed   $replace
     * @param string  $documentPartXML
     * @param integer $limit
     *
     * @return string
     */
    protected function setValueForPart($search, $replace, $documentPartXML, $limit)
    {
        // Shift-Enter
        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = preg_replace('~\R~u', '</w:t><w:br/><w:t>', $item);
            }
        } else {
            $replace = preg_replace('~\R~u', '</w:t><w:br/><w:t>', $replace);
        }

        // Note: we can't use the same function for both cases here, because of performance considerations.
        if (self::MAXIMUM_REPLACEMENTS_DEFAULT === $limit) {
            return str_replace($search, $replace, $documentPartXML);
        } else {
            $regExpEscaper = new RegExp();
            return preg_replace($regExpEscaper->escape($search), $replace, $documentPartXML, $limit);
        }
    }

    /**
     * Find all variables in $documentPartXML.
     *
     * @param string $documentPartXML
     *
     * @return string[]
     */
    protected function getVariablesForPart($documentPartXML)
    {
        preg_match_all('/\$\{([^<>{}]*?)}/i', $documentPartXML, $matches);

        return $matches[1];
    }

    /**
     * Get the name of the header file for $index.
     *
     * @param integer $index
     *
     * @return string
     */
    protected function getHeaderName($index)
    {
        return sprintf('word/header%d.xml', $index);
    }

    /**
     * @return string
     */
    protected function getMainPartName()
    {
        return 'word/document.xml';
    }

    /**
     * Get the name of the footer file for $index.
     *
     * @param integer $index
     *
     * @return string
     */
    protected function getFooterName($index)
    {
        return sprintf('word/footer%d.xml', $index);
    }

    /**
     * Find the start position of the nearest tag before $offset.
     *
     * @param string  $searchString The string we are searching in (the mainbody or an array element of Footers/Headers)
     * @param string  $tag  Fully qualified tag, for example: '<w:p>'
     * @param integer $offset Do not look from the beginning, but starting at $offset
     * @param boolean $throwException
     *
     * @return integer
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function findTagLeft(&$searchString, $tag, $offset = 0, $throwException = false)
    {
        $tagStart = strrpos(
            $searchString,
            substr($tag, 0, -1) . ' ',
            ((strlen($searchString) - $offset) * -1)
        );

        if (!$tagStart) {
            $tagStart = strrpos(
                $searchString,
                $tag,
                ((strlen($searchString) - $offset) * -1)
            );
        }
        if (!$tagStart) {
            return $this->failGraciously(
                "Can not find the start position of the item to clone.",
                $throwException,
                0
            );
        }

        return $tagStart;
    }

    /**
     * Find the end position of the nearest $tag after $offset.
     *
     * @param string  $searchString The string we are searching in (the MainPart or an array element of Footers/Headers)
     * @param string  $tag  Fully qualified tag, for example: '<w:p>'
     * @param integer $offset Do not look from the beginning, but starting at $offset
     *
     * @return integer
     */
    protected function findTagRight(&$searchString, $tag, $offset = 0)
    {
        $pos = strpos($searchString, $tag, $offset);
        if ($pos !== false) {
            return $pos + strlen($tag);
        } else {
            return 0;
        }
    }

    /**
     * Get a slice of a string.
     *
     * @param string  $searchString The string we are searching in (the MainPart or an array element of Footers/Headers)
     * @param integer $startPosition
     * @param integer $endPosition
     *
     * @return string
     */
    protected function getSlice(&$searchString, $startPosition, $endPosition = 0)
    {
        if (!$endPosition) {
            $endPosition = strlen($searchString);
        }

        return substr($searchString, $startPosition, ($endPosition - $startPosition));
    }
}
