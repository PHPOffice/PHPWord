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
 * @copyright   2010-2016 PHPWord contributors
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
use PhpOffice\PhpWord\Shared\Converter;

class TemplateProcessor
{
    const MAXIMUM_REPLACEMENTS_DEFAULT = -1;

    /**
     * sprintf template for fragment to insert an image
     *
     * sprintf arguments:
     * 1. d, width in EMU
     * 2. d, height in EMU
     * 3. s, graphic id (usually sequential, e.g. "1")
     * 4. s, graphic name (usually sequential with prefix, e.g. "Grafik 1")
     * 5. s, graphic filename (virtual, e.g. "MyLovelyHorse.jpg")
     * 6. s, relationship ID (see argument 1 for RELATIONSHIP_TEMPLATE)
     *
     * @see http://blogs.msdn.com/b/dmahugh/archive/2006/12/10/images-in-open-xml-documents.aspx
     */
    const IMAGE_TEMPLATE = '<w:drawing><wp:inline><wp:extent cx="%1$d" cy="%2$d"/><wp:docPr id="%3$s" name="%4$s"/><a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:nvPicPr><pic:cNvPr id="0" name="%5$s"/><pic:cNvPicPr/></pic:nvPicPr><pic:blipFill><a:blip r:embed="%6$s" cstate="print"/><a:stretch><a:fillRect/></a:stretch></pic:blipFill><pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="%1$d" cy="%2$d"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr></pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing>';

    /**
     * sprintf template for fragment to create an image relationship
     *
     * sprintf arguments:
     * 1: s, namespace prefix, including colon
     * 2: s, relationship ID (see argument 7 for IMAGE_TEMPLATE)
     * 3: s, image filename
     *
     * @see http://blogs.msdn.com/b/dmahugh/archive/2006/12/10/images-in-open-xml-documents.aspx
     * @see http://hastobe.net/blogs/stevemorgan/archive/2008/09/15/howto-insert-an-image-into-a-word-document-and-display-it-using-openxml.aspx
     */
    const RELATIONSHIP_TEMPLATE = '<%sRelationship Id="%s" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="%s"/>';

    /**
     * sprintf template for fragment to add a default content type
     *
     * sprintf arguments:
     * 1: s, namespace prefix, including colon
     * 2: s, extension
     * 3: s, MIME-type
     */
    const CONTENTTYPE_DEFAULT_TEMPLATE = '<%sDefault Extension="%s" ContentType="%s"/>';

    /**
     * sprintf template for fragment to add an override content type
     *
     * sprintf arguments:
     * 1: s, namespace prefix, including colon
     * 2: s, path to part file
     * 3: s, MIME-type
     */
    const CONTENTTYPE_OVERRIDE_TEMPLATE = '<%sOverride PartName="%s" ContentType="%s"/>';

    /**
     * Template for a new relationships file
     */
    const RELATIONSHIPS_FILE_TEMPLATE = <<<'ENDXML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"></Relationships>
ENDXML;

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
     * Document relationships (in XML format) of the temporary document.
     *
     * @var string
     */
    protected $tempDocumentRelationships;

    /**
     * Content of headers (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentHeaders = array();

    /**
     * Document header relationships (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentHeadersRelationships = array();

    /**
     * Content of footers (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentFooters = array();

    /**
     * Document footer relationships (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentFootersRelationships = array();

    /**
     * Document content types (in XML format) of the temporary document.
     *
     * @var string
     */
    protected $tempDocumentContentTypes;

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
            if (false !== $this->zipClass->locateName($this->getHeaderRelsName($index))) {
                $this->tempDocumentHeadersRelationships[$index] = $this->zipClass->getFromName($this->getHeaderRelsName($index));
            }
            $index++;
        }
        $index = 1;
        while (false !== $this->zipClass->locateName($this->getFooterName($index))) {
            $this->tempDocumentFooters[$index] = $this->fixBrokenMacros(
                $this->zipClass->getFromName($this->getFooterName($index))
            );
            if (false !== $this->zipClass->locateName($this->getFooterRelsName($index))) {
                $this->tempDocumentFootersRelationships[$index] = $this->zipClass->getFromName($this->getFooterRelsName($index));
            }
            $index++;
        }
        $this->tempDocumentMainPart = $this->fixBrokenMacros($this->zipClass->getFromName($this->getMainPartName()));
        $this->tempDocumentRelationships = $this->zipClass->getFromName($this->getMainPartRelsName());
        $this->tempDocumentContentTypes = $this->zipClass->getFromName($this->getContentTypesPartName());
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
     * @param string $search
     * @param integer $numberOfClones
     *
     * @return void
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function cloneRow($search, $numberOfClones)
    {
        if ('${' !== substr($search, 0, 2) && '}' !== substr($search, -1)) {
            $search = '${' . $search . '}';
        }

        $tagPos = strpos($this->tempDocumentMainPart, $search);
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

        $this->tempDocumentMainPart = $result;
    }

    /**
     * Clone a block.
     *
     * @param string $blockname
     * @param integer $clones
     * @param boolean $replace
     *
     * @return string|null
     */
    public function cloneBlock($blockname, $clones = 1, $replace = true)
    {
        $xmlBlock = null;
        preg_match(
            '/(<\?xml.*)(<w:p.*>\${' . $blockname . '}<\/w:.*?p>)(.*)(<w:p.*\${\/' . $blockname . '}<\/w:.*?p>)/is',
            $this->tempDocumentMainPart,
            $matches
        );

        if (isset($matches[3])) {
            $xmlBlock = $matches[3];
            $cloned = array();
            for ($i = 1; $i <= $clones; $i++) {
                $cloned[] = $xmlBlock;
            }

            if ($replace) {
                $this->tempDocumentMainPart = str_replace(
                    $matches[2] . $matches[3] . $matches[4],
                    implode('', $cloned),
                    $this->tempDocumentMainPart
                );
            }
        }

        return $xmlBlock;
    }

    /**
     * Replace a block.
     *
     * @param string $blockname
     * @param string $replacement
     *
     * @return void
     */
    public function replaceBlock($blockname, $replacement)
    {
        preg_match(
            '/(<\?xml.*)(<w:p.*>\${' . $blockname . '}<\/w:.*?p>)(.*)(<w:p.*\${\/' . $blockname . '}<\/w:.*?p>)/is',
            $this->tempDocumentMainPart,
            $matches
        );

        if (isset($matches[3])) {
            $this->tempDocumentMainPart = str_replace(
                $matches[2] . $matches[3] . $matches[4],
                $replacement,
                $this->tempDocumentMainPart
            );
        }
    }

    /**
     * Insert an image at locations specifed using an image placeholder
     *
     * The placeholder takes the form ${img:name}, or ${img:name:width:height}
     *
     * @param string $name        Image placeholder name (${img:$name})
     * @param string $srcFilename Path to image file to insert
     * @param string $width       Width of image, including units (e.g. 240pt);
     *                            will be read from image assuming 96dpi if not
     *                            supplied either here or as part of the
     *                            placeholder
     * @param string $height      Height of image, including units (e.g. 360pt);
     *                            will be read from image assuming 96dpi if not
     *                            supplied either here or as part of the
     *                            placeholder
     * @param string $mimeType    MIME-type of image; will be autodetected from
     *                            image if not supplied
     * @param string $filename    Name of file as it should be inserted in
     *                            document: the basename of $srcFilename if not
     *                            supplied
     *
     * @return \PhpOffice\PhpWord\Template
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function insertImage($name, $srcFilename, $width = null, $height = null, $mimeType = null, $filename = null)
    {
        if (($width === null) || ($width === null) || ($mimeType === null)) {
            $imageinfo = getimagesize($srcFilename);
            if (!empty($imageinfo)) {
                if ($width === null) {
                    $width = Converter::pixelToCm($imageinfo[0]);
                }
                if ($height === null) {
                    $height = Converter::pixelToCm($imageinfo[1]);
                }
                if ($mimeType === null) {
                    $mimeType = $imageinfo['mime'];
                }
            }
        }
        if ($filename === null) {
            $filename = basename($srcFilename);
        }
        $width = Converter::cssToEmu($width);
        $height = Converter::cssToEmu($height);

        $name = preg_replace('/^(?:\\$?{?img:)(.*)\\}?/', '$1', $name);

        $mediaPath = $this->addImageToArchive($srcFilename, $mimeType);

        foreach ($this->tempDocumentHeaders as $index => $header) {
            $tempHeaderRelationships = array_key_exists($index, $this->tempDocumentHeadersRelationships) ? $this->tempDocumentHeadersRelationships[$index] : null;
            $imageInsertResult = $this->insertImageForPart($header, $tempHeaderRelationships, $name, $mediaPath, $width, $height, $filename);
            if ($imageInsertResult) {
                list($this->tempDocumentHeaders[$index], $this->tempDocumentHeadersRelationships[$index]) = $imageInsertResult;
            }
        }

        $imageInsertResult = $this->insertImageForPart($this->tempDocumentMainPart, $this->tempDocumentRelationships, $name, $mediaPath, $width, $height, $filename);
        if ($imageInsertResult) {
            list($this->tempDocumentMainPart, $this->tempDocumentRelationships) = $imageInsertResult;
        }

        foreach ($this->tempDocumentFooters as $index => $footer) {
            $tempFooterRelationships = array_key_exists($index, $this->tempDocumentFootersRelationships) ? $this->tempDocumentFootersRelationships[$index] : null;
            $imageInsertResult = $this->insertImageForPart($footer, $tempFooterRelationships, $name, $mediaPath, $width, $height, $filename);
            if ($imageInsertResult) {
                list($this->tempDocumentFooters[$index], $this->tempDocumentFootersRelationships[$index]) = $imageInsertResult;
            }
        }

        return $this;
    }

    /**
     * Delete a block of text.
     *
     * @param string $blockname
     *
     * @return void
     */
    public function deleteBlock($blockname)
    {
        $this->replaceBlock($blockname, '');
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
        foreach ($this->tempDocumentHeadersRelationships as $index => $xml) {
            $this->zipClass->addFromString($this->getHeaderRelsName($index), $xml);
        }

        $this->zipClass->addFromString($this->getMainPartName(), $this->tempDocumentMainPart);
        $this->zipClass->addFromString($this->getMainPartRelsName(), $this->tempDocumentRelationships);

        foreach ($this->tempDocumentFooters as $index => $xml) {
            $this->zipClass->addFromString($this->getFooterName($index), $xml);
        }
        foreach ($this->tempDocumentFootersRelationships as $index => $xml) {
            $this->zipClass->addFromString($this->getFooterRelsName($index), $xml);
        }

        $this->zipClass->addFromString($this->getContentTypesPartName(), $this->tempDocumentContentTypes);

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
     * @param mixed $search
     * @param mixed $replace
     * @param string $documentPartXML
     * @param integer $limit
     *
     * @return string
     */
    protected function setValueForPart($search, $replace, $documentPartXML, $limit)
    {
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
     * Get the name of the header relationships file for $index
     * @param integer $index
     * @return string
     */
    protected function getHeaderRelsName($index)
    {
        return sprintf('word/_rels/header%d.xml.rels', $index);
    }

    /**
     * @return string
     */
    protected function getMainPartName()
    {
        return 'word/document.xml';
    }

    /**
     * Get the name of the relationships file for the main document
     *
     * @return string
     */
    protected function getMainPartRelsName()
    {
        return 'word/_rels/document.xml.rels';
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
     * Get the name of the footer relationships file for $index
     *
     * @param integer $index
     *
     * @return string
     */
    protected function getFooterRelsName($index)
    {
        return sprintf('word/_rels/footer%d.xml.rels', $index);
    }

    /**
     * Get the name of the content types file
     *
     * @return string
     */
    protected function getContentTypesPartName()
    {
        return '[Content_Types].xml';
    }

    /**
     * Find the start position of the nearest table row before $offset.
     *
     * @param integer $offset
     *
     * @return integer
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function findRowStart($offset)
    {
        $rowStart = strrpos($this->tempDocumentMainPart, '<w:tr ', ((strlen($this->tempDocumentMainPart) - $offset) * -1));

        if (!$rowStart) {
            $rowStart = strrpos($this->tempDocumentMainPart, '<w:tr>', ((strlen($this->tempDocumentMainPart) - $offset) * -1));
        }
        if (!$rowStart) {
            throw new Exception('Can not find the start position of the row to clone.');
        }

        return $rowStart;
    }

    /**
     * Find the end position of the nearest table row after $offset.
     *
     * @param integer $offset
     *
     * @return integer
     */
    protected function findRowEnd($offset)
    {
        return strpos($this->tempDocumentMainPart, '</w:tr>', $offset) + 7;
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

    /**
     * Add an image from file to the document archive (in word/media)
     *
     * @param $strFilename Path of file to add to achive
     * @param $mimeType    Mime type of the image
     *
     * @return string Internal path reference for media file (media/imageX.yyy)
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function addImageToArchive($strFilename, $mimeType = null)
    {
        $basename = basename($strFilename);
        if (strpos($basename, '.') !== false) {
            $extension = strtolower(substr($basename, strpos($basename, '.') + 1));
        } else {
            $extension = preg_replace('#^[^/]*/(\\w+)(?:\W.*)?#', '$1', strtolower($mimeType));
        }
        // special case where MIMEtype doesn't match usual file extension
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }
        $counter = 1;
        // find the next lowest file number
        while ($this->zipClass->locateName($mediaFileName = sprintf('word/media/image%d.%s', $counter, $extension)) !== false) {
            $counter++;
        }
        if (!$this->zipClass->addFile($strFilename, $mediaFileName)) {
            throw new Exception('Could not add image to archive');
        }
        // now make sure this extension/MIMEtype is referenced
        $regex = sprintf('/<(?:\\w+:)?Default(?:\\s*(?:Extension\\s*=\\s*"(%s)"|ContentType\\s*=\\s*"(%s)")){2}/i', preg_quote($extension, '/'), preg_quote($mimeType, '/'));
        $matches = null;
        if (preg_match($regex, $this->tempDocumentContentTypes, $matches)) {
            if ($matches[2] !== $mimeType) {
                // requires an Override
                $this->addContentTypeOverride($mediaFileName, $mimeType);
            }
        } else {
            // add a default content type
            $fragment = sprintf(static::CONTENTTYPE_DEFAULT_TEMPLATE, '$1', $extension, $mimeType);
            $this->tempDocumentContentTypes = preg_replace('/(?=<(\\w+:)?Override\b)/i', $fragment, $this->tempDocumentContentTypes, 1);
        }
        // references to the media must not include the path prefix word/
        return substr($mediaFileName, 5);
    }

    /**
     * Add a content-type override for an included file
     *
     * @param string $fileName Full internal name/path of file
     * @param string $mimeType Content-type for file
     *
     * @return \PhpOffice\PhpWord\Template
     */
    protected function addContentTypeOverride($fileName, $mimeType)
    {
        $fragment = sprintf(static::CONTENTTYPE_OVERRIDE_TEMPLATE, '$1', $fileName, $mimeType);
        $this->tempDocumentContentTypes = preg_replace('#(?=</(\\w+:)?Types>\\s*$)#i', $fragment, $this->tempDocumentContentTypes);
        return $this;
    }

    /**
     * Add a relationship to an image to a document part relationships XML
     *
     * Note that if the given relationship ID already exists then the XML is
     * returned unchanged, even if a the existing relationship is to a different
     * media file.
     *
     * @param string $tempPartRelationships Relationships XML
     * @param string $mediaFileName    Internal path reference for media file
     * @param string $relId            ID for this relationship
     *
     * @return string
     */
    protected function addImageRelationship($tempPartRelationships, $mediaFileName, $relId)
    {
        if (!$tempPartRelationships) {
            $tempPartRelationships = static::RELATIONSHIPS_FILE_TEMPLATE;
        }
        if (!preg_match('/\bId\s*=\\\s*"([^"]+)"/i', $tempPartRelationships)) {
            $relXML = sprintf(static::RELATIONSHIP_TEMPLATE, '$1', $relId, $mediaFileName);
            $tempPartRelationships = preg_replace('#(?=</(\\w+:)?Relationships>\\s*$)#i', $relXML, $tempPartRelationships);
        }
        return $tempPartRelationships;
    }

    /**
     * Get the relationship ID for the given part and media file
     *
     * If this relationship already exists then the existing ID is returned,
     * otherwise the next ID in the sequence rIdX is returned (this relationship
     * does howver not yet actually exist!)
     *
     * @param string $tempPartRelationships Relationships XML
     * @param string $mediaFileName    Internal path reference for media file
     *
     * @return string
     */
    protected function getPartRelationshipId($tempPartRelationships, $mediaFileName)
    {
        if (!$tempPartRelationships) {
            // not yet any relationships file
            $relId = 'rId1';
        } else {
            $matches = null;
            $regex = sprintf('/<(?:\\w+:)?Relationship\\s+Id\\s*=\\s*"([^"]+)"[^>]+Target\\s*=\\s*"%1$s"/i', preg_quote($mediaFileName, '/'));
            if (preg_match($regex, $tempPartRelationships, $matches)) {
                // relationship already exists: grab ID of it
                $relId = $matches[1];
            } else {
                // work out next relationship number and use it
                $relIds = null;
                $matches = preg_match_all('/Id\\s*=\\s*"([^"]+)"/i', $tempPartRelationships, $relIds);
                if ($matches) {
                    $lastId = 0;
                    foreach ($relIds[1] as $existingRelId) {
                        if (preg_match('/^rId\\d+/i', $existingRelId)) {
                            $lastId = max($lastId, intval(substr($existingRelId, 3), 10));
                        }
                    }
                    $relId = sprintf('rId%d', ++$lastId);
                } else {
                    // I guess the relationships file was empty...
                    $relId = 'rId1';
                }
            }
        }
        return $relId;
    }

    /**
     * Insert an image into placeholders in a document part
     *
     * If a placeholder was found and the image inserted then an array with
     * the changed $documentPartXML at index 0 and $partRelationshipsXML at
     * index 1. If no changes were made then false is returned.
     *
     * @param string $tempDocumentPart      XML of document part
     * @param string $tempPartRelationships XML of document part relationships
     * @param string $name                 Image placeholder name (${img:$name})
     * @param string $mediaFileName        Internal path reference for media
     *                                     file
     * @param number $width                Width of image in EMU
     * @param number $height               Height of image in EMU
     * @param string $filename             Name of file as it should be inserted
     *                                     in document
     *
     * @return string[]|false
     */
    protected function insertImageForPart($tempDocumentPart, $tempPartRelationships, $name, $mediaFileName, $width, $height, $filename)
    {
        $relId = $this->getPartRelationshipId($tempPartRelationships, $mediaFileName);
        // hacks for no class scope in callback function in PHP5.3
        $class = __CLASS__;
        $count = 0;
        $graphicIdMatches = preg_match_all('/(?<=<wp:docPr id=")[^"]+/u', $tempDocumentPart, $graphicIds, PREG_PATTERN_ORDER);
        if ($graphicIdMatches) {
            $nextGraphicId = max($graphicIds[0]) + 1;
        } else {
            $nextGraphicId = 0;
        }
        $tempDocumentPart = preg_replace_callback(
            '/(<w:t(?:>|\s[^>]*>))?\\$((?:<[^>]+>)*)\\{([^\\}]+)\\}(<\\/w:t>)?/u',
            function ($match) use ($relId, $name, $class, $width, $height, $filename, &$nextGraphicId) {
                $variable = explode(':', strip_tags($match[3]));
                if ((count($variable) > 1) && ($variable[0] == 'img') && ($variable[1] == $name)) {
                    // we just gotta hope this random element Id will be unique!
                    $graphicId = $nextGraphicId++;
                    if (count($variable) > 3) {
                        $myWidth = Converter::cssToEmu($variable[2]);
                        $myHeight = Converter::cssToEmu($variable[3]);
                    } else {
                        $myWidth = $width;
                        $myHeight = $height;
                    }
                    $block = sprintf($class::IMAGE_TEMPLATE, $myWidth, $myHeight, $graphicId, sprintf('Graphic %d', $graphicId), $filename, $relId);
                    // sort out opening and closing text block tags if we've not removed both
                    if (!($match[1] && $match[4])) {
                        if (!$match[1]) {
                            /*
                             * we remove a closing text tag but not an opening one,
                             * so need to close the text block before the image
                             */
                            $block = '</w:t>' . $block;
                        }
                        if (!$match[4]) {
                            /*
                             * we remove an opening text tag but not a closing one,
                             * so need to start a new text block after the image
                             */
                            $block .= '<w:t>';
                        }
                    }
                    return $block;
                } else {
                    return $match[0];
                }
            },
            $tempDocumentPart,
            -1,
            $count
        );
        // only need to add relationship if image actually added to this part
        if ($count > 0) {
            $tempPartRelationships = $this->addImageRelationship($tempPartRelationships, $mediaFileName, $relId);
            return array($tempDocumentPart, $tempPartRelationships);
        } else {
            return false;
        }
    }
}
