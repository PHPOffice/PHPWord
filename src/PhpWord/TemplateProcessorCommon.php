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

use PhpOffice\PhpWord\Escaper\RegExp;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\Text;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Shared\ZipArchive;

class TemplateProcessorCommon
{
    const MAXIMUM_REPLACEMENTS_DEFAULT = -1;

    /**
     * ZipArchive object.
     *
     * @var mixed
     */
    protected $zipClass;

    /**
     * @var string Temporary document filename (with path)
     */
    protected $tempDocumentFilename;

    /**
     * Content of main document part (in XML format) of the temporary document
     *
     * @var string
     */
    protected $tempDocumentMainPart;

    /**
     * Content of settings part (in XML format) of the temporary document
     *
     * @var string
     */
    protected $tempDocumentSettingsPart;

    /**
     * Content of headers (in XML format) of the temporary document
     *
     * @var string[]
     */
    protected $tempDocumentHeaders = array();

    /**
     * Content of footers (in XML format) of the temporary document
     *
     * @var string[]
     */
    protected $tempDocumentFooters = array();

    /**
     * Document relations (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentRelations = array();

    /**
     * Document content types (in XML format) of the temporary document.
     *
     * @var string
     */
    protected $tempDocumentContentTypes = '';

    /**
     * new inserted images list
     *
     * @var string[]
     */
    protected $tempDocumentNewImages = array();

    /**
     * Expose zip class
     *
     * To replace an image: $templateProcessor->zip()->AddFromString("word/media/image1.jpg", file_get_contents($file));<br>
     * To read a file: $templateProcessor->zip()->getFromName("word/media/image1.jpg");
     *
     * @return \PhpOffice\PhpWord\Shared\ZipArchive
     */
    public function zip()
    {
        return $this->zipClass;
    }

    /**
     * @param string $xml
     * @param \XSLTProcessor $xsltProcessor
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return string
     */
    protected function transformSingleXml($xml, $xsltProcessor)
    {
        if (\PHP_VERSION_ID < 80000) {
            $orignalLibEntityLoader = libxml_disable_entity_loader(true);
        }
        $domDocument = new \DOMDocument();
        if (false === $domDocument->loadXML($xml)) {
            throw new Exception('Could not load the given XML document.');
        }

        $transformedXml = $xsltProcessor->transformToXml($domDocument);
        if (false === $transformedXml) {
            throw new Exception('Could not transform the given XML document.');
        }
        if (\PHP_VERSION_ID < 80000) {
            libxml_disable_entity_loader($orignalLibEntityLoader);
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
            unset($item);
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
        if (!Text::isUTF8($subject)) {
            $subject = utf8_encode($subject);
        }

        return $subject;
    }


    /**
     * Set values from a one-dimensional array of "variable => value"-pairs.
     *
     * @param array $values
     */
    public function setValues(array $values)
    {
        foreach ($values as $macro => $replace) {
            $this->setValue($macro, $replace);
        }
    }

    protected function getImageArgs($varNameWithArgs)
    {
        $varElements = explode(':', $varNameWithArgs);
        array_shift($varElements); // first element is name of variable => remove it

        $varInlineArgs = array();
        // size format documentation: https://msdn.microsoft.com/en-us/library/documentformat.openxml.vml.shape%28v=office.14%29.aspx?f=255&MSPPError=-2147217396
        foreach ($varElements as $argIdx => $varArg) {
            if (strpos($varArg, '=')) { // arg=value
                list($argName, $argValue) = explode('=', $varArg, 2);
                $argName = strtolower($argName);
                if ($argName == 'size') {
                    list($varInlineArgs['width'], $varInlineArgs['height']) = explode('x', $argValue, 2);
                } else {
                    $varInlineArgs[strtolower($argName)] = $argValue;
                }
            } elseif (preg_match('/^([0-9]*[a-z%]{0,2}|auto)x([0-9]*[a-z%]{0,2}|auto)$/i', $varArg)) { // 60x40
                list($varInlineArgs['width'], $varInlineArgs['height']) = explode('x', $varArg, 2);
            } else { // :60:40:f
                switch ($argIdx) {
                    case 0:
                        $varInlineArgs['width'] = $varArg;
                        break;
                    case 1:
                        $varInlineArgs['height'] = $varArg;
                        break;
                    case 2:
                        $varInlineArgs['ratio'] = $varArg;
                        break;
                }
            }
        }

        return $varInlineArgs;
    }

    private function chooseImageDimension($baseValue, $inlineValue, $defaultValue)
    {
        $value = $baseValue;
        if (is_null($value) && isset($inlineValue)) {
            $value = $inlineValue;
        }
        if (!preg_match('/^([0-9]*(cm|mm|in|pt|pc|px|%|em|ex|)|auto)$/i', $value)) {
            $value = null;
        }
        if (is_null($value)) {
            $value = $defaultValue;
        }
        if (is_numeric($value)) {
            $value .= 'px';
        }

        return $value;
    }

    private function fixImageWidthHeightRatio(&$width, &$height, $actualWidth, $actualHeight)
    {
        $imageRatio = $actualWidth / $actualHeight;

        if (($width === '') && ($height === '')) { // defined size are empty
            $width = $actualWidth . 'px';
            $height = $actualHeight . 'px';
        } elseif ($width === '') { // defined width is empty
            $heightFloat = (float) $height;
            $widthFloat = $heightFloat * $imageRatio;
            $matches = array();
            preg_match("/\d([a-z%]+)$/", $height, $matches);
            $width = $widthFloat . $matches[1];
        } elseif ($height === '') { // defined height is empty
            $widthFloat = (float) $width;
            $heightFloat = $widthFloat / $imageRatio;
            $matches = array();
            preg_match("/\d([a-z%]+)$/", $width, $matches);
            $height = $heightFloat . $matches[1];
        } else { // we have defined size, but we need also check it aspect ratio
            $widthMatches = array();
            preg_match("/\d([a-z%]+)$/", $width, $widthMatches);
            $heightMatches = array();
            preg_match("/\d([a-z%]+)$/", $height, $heightMatches);
            // try to fix only if dimensions are same
            if ($widthMatches[1] == $heightMatches[1]) {
                $dimention = $widthMatches[1];
                $widthFloat = (float) $width;
                $heightFloat = (float) $height;
                $definedRatio = $widthFloat / $heightFloat;

                if ($imageRatio > $definedRatio) { // image wider than defined box
                    $height = ($widthFloat / $imageRatio) . $dimention;
                } elseif ($imageRatio < $definedRatio) { // image higher than defined box
                    $width = ($heightFloat * $imageRatio) . $dimention;
                }
            }
        }
    }

    protected function prepareImageAttrs($replaceImage, $varInlineArgs)
    {
        // get image path and size
        $width = null;
        $height = null;
        $ratio = null;

        // a closure can be passed as replacement value which after resolving, can contain the replacement info for the image
        // use case: only when a image if found, the replacement tags can be generated
        if (is_callable($replaceImage)) {
            $replaceImage = $replaceImage();
        }

        if (is_array($replaceImage) && isset($replaceImage['path'])) {
            $imgPath = $replaceImage['path'];
            if (isset($replaceImage['width'])) {
                $width = $replaceImage['width'];
            }
            if (isset($replaceImage['height'])) {
                $height = $replaceImage['height'];
            }
            if (isset($replaceImage['ratio'])) {
                $ratio = $replaceImage['ratio'];
            }
        } else {
            $imgPath = $replaceImage;
        }

        $width = $this->chooseImageDimension($width, isset($varInlineArgs['width']) ? $varInlineArgs['width'] : null, 115);
        $height = $this->chooseImageDimension($height, isset($varInlineArgs['height']) ? $varInlineArgs['height'] : null, 70);

        $imageData = @getimagesize($imgPath);
        if (!is_array($imageData)) {
            throw new Exception(sprintf('Invalid image: %s', $imgPath));
        }
        list($actualWidth, $actualHeight, $imageType) = $imageData;

        // fix aspect ratio (by default)
        if (is_null($ratio) && isset($varInlineArgs['ratio'])) {
            $ratio = $varInlineArgs['ratio'];
        }
        if (is_null($ratio) || !in_array(strtolower($ratio), array('', '-', 'f', 'false'))) {
            $this->fixImageWidthHeightRatio($width, $height, $actualWidth, $actualHeight);
        }

        $imageAttrs = array(
            'src'    => $imgPath,
            'mime'   => image_type_to_mime_type($imageType),
            'width'  => $width,
            'height' => $height,
        );

        return $imageAttrs;
    }

    /**
     * Clones a table row and populates it's values from a two-dimensional array in a template document.
     *
     * @param string $search
     * @param array $values
     */
    public function cloneRowAndSetValues($search, $values)
    {
        $this->cloneRow($search, count($values));

        foreach ($values as $rowKey => $rowData) {
            $rowNumber = $rowKey + 1;
            foreach ($rowData as $macro => $replace) {
                $this->setValue($macro . '#' . $rowNumber, $replace);
            }
        }
    }

    /**
     * Delete a block of text.
     *
     * @param string $blockname
     */
    public function deleteBlock($blockname)
    {
        $this->replaceBlock($blockname, '');
    }

    /**
     * @param string $fileName
     * @param string $xml
     */
    protected function savePartWithRels($fileName, $xml)
    {
        $this->zipClass->addFromString($fileName, $xml);
        if (isset($this->tempDocumentRelations[$fileName])) {
            $relsFileName = $this->getRelationsName($fileName);
            $this->zipClass->addFromString($relsFileName, $this->tempDocumentRelations[$fileName]);
        }
    }

    /**
     * Saves the result document to the user defined file.
     *
     * @since 0.8.0
     *
     * @param string $fileName
     */
    public function saveAs($fileName)
    {
        $tempFileName = $this->save();

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        /*
         * Note: we do not use `rename` function here, because it loses file ownership data on Windows platform.
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
     * @param string $documentPart The document part in XML representation
     *
     * @return string
     */
    protected function fixBrokenMacros($documentPart)
    {
        return preg_replace_callback(
            '/\$(?:\{|[^{$]*\>\{)[^}$]*\}/U',
            function ($match) {
                return strip_tags($match[0]);
            },
            $documentPart
        );
    }

    /**
     * Find and replace macros in the given XML section.
     *
     * @param mixed $search
     * @param mixed $replace
     * @param string $documentPartXML
     * @param int $limit
     *
     * @return string
     */
    protected function setValueForPart($search, $replace, $documentPartXML, $limit)
    {
        // Note: we can't use the same function for both cases here, because of performance considerations.
        if (self::MAXIMUM_REPLACEMENTS_DEFAULT === $limit) {
            return str_replace($search, $replace, $documentPartXML);
        }
        $regExpEscaper = new RegExp();

        return preg_replace($regExpEscaper->escape($search), $replace, $documentPartXML, $limit);
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
        $matches = array();
        preg_match_all('/\$\{(.*?)}/i', $documentPartXML, $matches);

        return $matches[1];
    }

    /**
     * Get a slice of a string.
     *
     * @param int $startPosition
     * @param int $endPosition
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
     * Replaces variable names in cloned
     * rows/blocks with indexed names
     *
     * @param int $count
     * @param string $xmlBlock
     *
     * @return string
     */
    protected function indexClonedVariables($count, $xmlBlock)
    {
        $results = array();
        for ($i = 1; $i <= $count; $i++) {
            $results[] = preg_replace('/\$\{([^:]*?)(:.*?)?\}/', '\${\1#' . $i . '\2}', $xmlBlock);
        }

        return $results;
    }

    /**
     * Raplaces variables with values from array, array keys are the variable names
     *
     * @param array $variableReplacements
     * @param string $xmlBlock
     *
     * @return string[]
     */
    protected function replaceClonedVariables($variableReplacements, $xmlBlock)
    {
        $results = array();
        foreach ($variableReplacements as $replacementArray) {
            $localXmlBlock = $xmlBlock;
            foreach ($replacementArray as $search => $replacement) {
                $localXmlBlock = $this->setValueForPart(self::ensureMacroCompleted($search), $replacement, $localXmlBlock, self::MAXIMUM_REPLACEMENTS_DEFAULT);
            }
            $results[] = $localXmlBlock;
        }

        return $results;
    }

    /**
     * Replace an XML block surrounding a macro with a new block
     *
     * @param string $macro Name of macro
     * @param string $block New block content
     * @param string $blockType XML tag type of block
     * @return \PhpOffice\PhpWord\TemplateProcessor Fluent interface
     */
    public function replaceXmlBlock($macro, $block, $blockType = 'w:p')
    {
        $where = $this->findContainingXmlBlockForMacro($macro, $blockType);
        if (is_array($where)) {
            $this->tempDocumentMainPart = $this->getSlice(0, $where['start']) . $block . $this->getSlice($where['end']);
        }

        return $this;
    }

    /**
     * Find start and end of XML block containing the given macro
     * e.g. <w:p>...${macro}...</w:p>
     *
     * Note that only the first instance of the macro will be found
     *
     * @param string $macro Name of macro
     * @param string $blockType XML tag for block
     * @return bool|int[] FALSE if not found, otherwise array with start and end
     */
    protected function findContainingXmlBlockForMacro($macro, $blockType = 'w:p')
    {
        $macroPos = $this->findMacro($macro);
        if (0 > $macroPos) {
            return false;
        }
        $start = $this->findXmlBlockStart($macroPos, $blockType);
        if (0 > $start) {
            return false;
        }
        $end = $this->findXmlBlockEnd($start, $blockType);
        //if not found or if resulting string does not contain the macro we are searching for
        if (0 > $end || strstr($this->getSlice($start, $end), $macro) === false) {
            return false;
        }

        return array('start' => $start, 'end' => $end);
    }

    /**
     * Find the position of (the start of) a macro
     *
     * Returns -1 if not found, otherwise position of opening $
     *
     * Note that only the first instance of the macro will be found
     *
     * @param string $search Macro name
     * @param int $offset Offset from which to start searching
     * @return int -1 if macro not found
     */
    protected function findMacro($search, $offset = 0)
    {
        $search = static::ensureMacroCompleted($search);
        $pos = strpos($this->tempDocumentMainPart, $search, $offset);

        return ($pos === false) ? -1 : $pos;
    }

    /**
     * Find the start position of the nearest XML block start before $offset
     *
     * @param int $offset    Search position
     * @param string  $blockType XML Block tag
     * @return int -1 if block start not found
     */
    protected function findXmlBlockStart($offset, $blockType)
    {
        $reverseOffset = (strlen($this->tempDocumentMainPart) - $offset) * -1;
        // first try XML tag with attributes
        $blockStart = strrpos($this->tempDocumentMainPart, '<' . $blockType . ' ', $reverseOffset);
        // if not found, or if found but contains the XML tag without attribute
        if (false === $blockStart || strrpos($this->getSlice($blockStart, $offset), '<' . $blockType . '>')) {
            // also try XML tag without attributes
            $blockStart = strrpos($this->tempDocumentMainPart, '<' . $blockType . '>', $reverseOffset);
        }

        return ($blockStart === false) ? -1 : $blockStart;
    }

    /**
     * Find the nearest block end position after $offset
     *
     * @param int $offset    Search position
     * @param string  $blockType XML Block tag
     * @return int -1 if block end not found
     */
    protected function findXmlBlockEnd($offset, $blockType)
    {
        $blockEndStart = strpos($this->tempDocumentMainPart, '</' . $blockType . '>', $offset);
        // return position of end of tag if found, otherwise -1

        return ($blockEndStart === false) ? -1 : $blockEndStart + 3 + strlen($blockType);
    }

    /**
     * Returns true if string contains a macro that is not in it's own w:r
     *
     * @param string $text
     * @return bool
     */
    protected function textNeedsSplitting($text)
    {
        return preg_match('/[^>]\${|}[^<]/i', $text) == 1;
    }
}
