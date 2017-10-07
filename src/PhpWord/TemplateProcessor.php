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
        } else {
            $xml = $this->transformSingleXml($xml, $xsltProcessor);
        }

        return $xml;
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

        $this->tempDocumentHeaders = $this->transformXml($this->tempDocumentHeaders, $xsltProcessor);
        $this->tempDocumentMainPart = $this->transformXml($this->tempDocumentMainPart, $xsltProcessor);
        $this->tempDocumentFooters = $this->transformXml($this->tempDocumentFooters, $xsltProcessor);
    }

    /**
     * @param string $macro
     *
     * @return string
     */
    protected static function ensureMacroCompleted($macro)
    {
        if (substr($macro, 0, 2) !== '${' && substr($macro, -1) !== '}') {
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

        $this->tempDocumentHeaders = $this->setValueForPart($search, $replace, $this->tempDocumentHeaders, $limit);
        $this->tempDocumentMainPart = $this->setValueForPart($search, $replace, $this->tempDocumentMainPart, $limit);
        $this->tempDocumentFooters = $this->setValueForPart($search, $replace, $this->tempDocumentFooters, $limit);
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
     * @param bool    $throwexception
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
        $throwexception = false
    ) {
        if ('${' !== substr($search, 0, 2) && '}' !== substr($search, -1)) {
            $search = '${' . $search . '}';
        }

        $tagPos = strpos($this->tempDocumentMainPart, $search);
        if (!$tagPos) {
            if ($throwexception) {
                throw new Exception(
                    "Can not clone row, template variable not found or variable contains markup."
                );
            } else {
                return null;
            }
        }

        $rowStart = $this->findTagLeft('<w:tr>', $tagPos, $throwexception); // findRowStart
        $rowEnd = $this->findTagRight('</w:tr>', $tagPos); // findRowEnd
        $xmlRow = $this->getSlice($rowStart, $rowEnd);

        // Check if there's a cell spanning multiple rows.
        if (preg_match('#<w:vMerge w:val="restart"/>#', $xmlRow)) {
            // $extraRowStart = $rowEnd;
            $extraRowEnd = $rowEnd;
            while (true) {
                $extraRowStart = $this->findTagLeft('<w:tr>', $extraRowEnd + 1, $throwexception); // findRowStart
                $extraRowEnd = $this->findTagRight('</w:tr>', $extraRowEnd + 1); // findRowEnd

                if (!$extraRowEnd) {
                    break;
                }

                // If tmpXmlRow doesn't contain continue, this row is no longer part of the spanned row.
                $tmpXmlRow = $this->getSlice($extraRowStart, $extraRowEnd);
                if (!preg_match('#<w:vMerge/>#', $tmpXmlRow)
                    && !preg_match('#<w:vMerge w:val="continue" />#', $tmpXmlRow)
                ) {
                    break;
                }
                // This row was a spanned row, update $rowEnd and search for the next row.
                $rowEnd = $extraRowEnd;
            }
            $xmlRow = $this->getSlice($rowStart, $rowEnd);
        }

        if ($replace) {
            $result = $this->getSlice(0, $rowStart);
            for ($i = 1; $i <= $numberOfClones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlRow);
                } else {
                    $result .= $xmlRow;
                }
            }
            $result .= $this->getSlice($rowEnd);

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
     * @param boolean $throwexception
     *
     * @return string|null
     */
    public function cloneBlock(
        $blockname,
        $clones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwexception = false
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
                $throwexception
            );
        }

        $startTagPos = strpos($this->tempDocumentMainPart, $startSearch);
        $endTagPos = strpos($this->tempDocumentMainPart, $endSearch, $startTagPos);

        if (!$startTagPos || !$endTagPos) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find block '$blockname', template variable not found or variable contains markup."
                );
            } else {
                return null; // Block not found, return null
            }
        }

        $startBlockStart = $this->findTagLeft('<w:p>', $startTagPos, $throwexception); // findBlockStart()
        $startBlockEnd = $this->findTagRight('</w:p>', $startTagPos); // findBlockEnd()
        // $xmlStart = $this->getSlice($startBlockStart, $startBlockEnd);

        if (!$startBlockStart || !$startBlockEnd) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find start paragraph around block '$blockname'"
                );
            } else {
                return false;
            }
        }

        $endBlockStart = $this->findTagLeft('<w:p>', $endTagPos, $throwexception); // findBlockStart()
        $endBlockEnd = $this->findTagRight('</w:p>', $endTagPos); // findBlockEnd()
        // $xmlEnd = $this->getSlice($endBlockStart, $endBlockEnd);

        if (!$endBlockStart || !$endBlockEnd) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find end paragraph around block '$blockname'"
                );
            } else {
                return false;
            }
        }

        if ($startBlockEnd == $endBlockEnd) { // inline block
            $startBlockStart = $startTagPos;
            $startBlockEnd = $startTagPos + strlen($startSearch);
            $endBlockStart = $endTagPos;
            $endBlockEnd = $endTagPos + strlen($endSearch);
        }

        $xmlBlock = $this->getSlice($startBlockEnd, $endBlockStart);

        if ($replace) {
            $result = $this->getSlice(0, $startBlockStart);
            for ($i = 1; $i <= $clones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlBlock);
                } else {
                    $result .= $xmlBlock;
                }
            }
            $result .= $this->getSlice($endBlockEnd);

            $this->tempDocumentMainPart = $result;
        }

        return $xmlBlock;
    }

    /**
     * Clone a segment.
     *
     * @param string  $needle
     * @param string  $xmltag
     * @param string  $docpart
     * @param integer $clones
     * @param boolean $replace
     * @param boolean $incrementVariables
     * @param boolean $throwexception
     *
     * @return string|null
     */
    public function cloneSegment(
        $needle,
        $xmltag,
        $docpart = 'MainPart',
        $clones = 1,
        $replace = true,
        $incrementVariables = true,
        $throwexception = false
    ) {
        $needlePos = strpos($this->{"tempDocument$docpart"}, $needle);

        if (!$needlePos) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find segment '$needle', text not found or text contains markup."
                );
            } else {
                return null; // Segment not found, return null
            }
        }

        $startSegmentStart = $this->findTagLeft("<$xmltag>", $needlePos, $throwexception);
        $endSegmentEnd = $this->findTagRight("</$xmltag>", $needlePos);

        if (!$startSegmentStart || !$endSegmentEnd) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find <$xmltag> around segment '$needle'"
                );
            } else {
                return false;
            }
        }

        $xmlSegment = $this->getSlice($startSegmentStart, $endSegmentEnd);

        if ($replace) {
            $result = $this->getSlice(0, $startSegmentStart);
            for ($i = 1; $i <= $clones; $i++) {
                if ($incrementVariables) {
                    $result .= preg_replace('/\$\{(.*?)\}/', '\${\\1#' . $i . '}', $xmlSegment);
                } else {
                    $result .= $xmlSegment;
                }
            }
            $result .= $this->getSlice($endSegmentEnd);

            $this->{"tempDocument$docpart"} = $result;
        }

        return $xmlSegment;
    }

    /**
     * Get a block. (first block found)
     *
     * @param string  $blockname
     * @param boolean $throwexception
     *
     * @return string|null
     */
    public function getBlock($blockname, $throwexception = false)
    {
        return $this->cloneBlock($blockname, 1, false, false, $throwexception);
    }

    /**
     * Get a segment. (first segment found)
     *
     * @param string  $needle
     * @param string  $xmltag
     * @param string  $docpart
     * @param boolean $throwexception
     *
     * @return string|null
     */
    public function getSegment($needle, $xmltag, $docpart = 'MainPart', $throwexception = false)
    {
        return $this->cloneSegment($needle, $xmltag, $docpart, 1, false, false, $throwexception);
    }

    /**
     * Get a row. (first block found)
     *
     * @param string  $rowname
     * @param boolean $throwexception
     *
     * @return string|null
     */
    public function getRow($rowname, $throwexception = false)
    {
        return $this->cloneRow($rowname, 1, false, false, $throwexception);
    }

    /**
     * Replace a block.
     *
     * @param string  $blockname
     * @param string  $replacement
     * @param boolean $throwexception
     *
     * @return false on no replacement, true on replacement
     */
    public function replaceBlock($blockname, $replacement = '', $throwexception = false)
    {
        $startSearch = '${'  . $blockname . '}';
        $endSearch   = '${/' . $blockname . '}';

        if (substr($blockname, -1) == '/') { // singleton/closed block
            return $this->replaceSegment($startSearch, 'w:p', $replacement, 'MainPart', $throwexception);
        }

        $startTagPos = strpos($this->tempDocumentMainPart, $startSearch);
        $endTagPos = strpos($this->tempDocumentMainPart, $endSearch, $startTagPos);

        if (!$startTagPos || !$endTagPos) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find block '$blockname', template variable not found or variable contains markup."
                );
            } else {
                return false;
            }
        }

        $startBlockStart = $this->findTagLeft('<w:p>', $startTagPos, $throwexception); // findBlockStart()
        $endBlockEnd = $this->findTagRight('</w:p>', $endTagPos); // findBlockEnd()

        if (!$startBlockStart || !$endBlockEnd) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find end paragraph around block '$blockname'"
                );
            } else {
                return false;
            }
        }

        $startBlockEnd = $this->findTagRight('</w:p>', $startTagPos); // findBlockEnd()
        if ($startBlockEnd == $endBlockEnd) { // inline block
            $startBlockStart = $startTagPos;
            $endBlockEnd = $endTagPos + strlen($endSearch);
        }

        $this->tempDocumentMainPart =
            $this->getSlice(0, $startBlockStart)
            . $replacement
            . $this->getSlice($endBlockEnd);

        return true;
    }


    /**
     * Replace a segment.
     *
     * @param string  $needle
     * @param string  $xmltag
     * @param string  $replacement
     * @param string  $docpart
     * @param boolean $throwexception
     *
     * @return false on no replacement, true on replacement
     */
    public function replaceSegment($needle, $xmltag, $replacement = '', $docpart = 'MainPart', $throwexception = false)
    {
        $TagPos = strpos($this->{"tempDocument$docpart"}, $needle);

        if ($TagPos === false) {
            if ($throwexception) {
                throw new Exception(
                    "Can not find segment '$needle', text not found or text contains markup."
                );
            } else {
                return false;
            }
        }

        $SegmentStart = $this->findTagLeft("<$xmltag>", $TagPos, $throwexception);
        $SegmentEnd = $this->findTagRight("</$xmltag>", $TagPos);

        $this->{"tempDocument$docpart"} =
            $this->getSlice(0, $SegmentStart)
            . $replacement
            . $this->getSlice($SegmentEnd);

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
     * @param string $needle
     * @param string $xmltag
     * @param string $docpart
     *
     * @return true on segment found and deleted, false on segment not found.
     */
    public function deleteSegment($needle, $xmltag, $docpart = 'MainPart')
    {
        return $this->replaceSegment($needle, $xmltag, '', $docpart, false);
    }

    /**
     * Saves the result document.
     *
     * @return string
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
     *
     * @param string $documentPart The document part in XML representation.
     *
     * @return string
     */
    protected function fixBrokenMacros($documentPart)
    {
        $fixedDocumentPart = $documentPart;

        $fixedDocumentPart = preg_replace_callback(
            '|\$[^{]*\{[^}]*\}|U',
            function ($match) {
                return strip_tags($match[0]);
            },
            $fixedDocumentPart
        );

        return $fixedDocumentPart;
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
        preg_match_all('/\$\{(.*?)}/i', $documentPartXML, $matches);

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
     * @param string $tag
     * @param integer $offset
     * @param boolean $throwexception
     *
     * @return integer
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function findTagLeft($tag, $offset = 0, $throwexception = false)
    {
        $tagStart = strrpos(
            $this->tempDocumentMainPart,
            substr($tag, 0, -1) . ' ',
            ((strlen($this->tempDocumentMainPart) - $offset) * -1)
        );

        if (!$tagStart) {
            $tagStart = strrpos(
                $this->tempDocumentMainPart,
                $tag,
                ((strlen($this->tempDocumentMainPart) - $offset) * -1)
            );
        }
        if (!$tagStart) {
            if ($throwexception) {
                throw new Exception('Can not find the start position of the item to clone.');
            } else {
                return 0;
            }
        }

        return $tagStart;
    }

    /**
     * Find the end position of the nearest $tag after $offset.
     *
     * @param string $tag
     * @param integer $offset
     *
     * @return integer
     */
    protected function findTagRight($tag, $offset = 0)
    {
        $pos = strpos($this->tempDocumentMainPart, $tag, $offset);
        if ($pos !== false) {
            return $pos + strlen($tag);
        } else {
            return 0;
        }
    }

    /**
     * Get a slice of a string.
     *
     * @param integer $startPosition
     * @param integer $endPosition
     *
     * @return string
     */
    protected function getSlice($startPosition, $endPosition = 0)
    {
        if (!$endPosition) {
            $endPosition = strlen($this->tempDocumentMainPart);
        }

        return substr($this->tempDocumentMainPart, $startPosition, ($endPosition - $startPosition));
    }
}
