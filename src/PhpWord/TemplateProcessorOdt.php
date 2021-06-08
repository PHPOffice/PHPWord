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
 * @see       https://github.com/PHPOffice/PHPWord
 * @copyright 2010-2018 PHPWord contributors
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\Text;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Shared\ZipArchive;

class TemplateProcessorOdt extends TemplateProcessorCommon
{
    /**
     * @since 0.12.0 Throws CreateTemporaryFileException and CopyFileException instead of Exception
     *
     * @param string $documentTemplate The fully qualified template filename
     *
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     */

    /**
     * Document manifest (in XML format) of the temporary document.
     *
     * @var string[]
     */
    protected $tempDocumentManifest = '';

    public function __construct($documentTemplate)
    {
        // Temporary document filename initialization
        $this->tempDocumentFilename = tempnam(Settings::getTempDir(), 'PhpWord');
        if (false === $this->tempDocumentFilename) {
            throw new CreateTemporaryFileException(); // @codeCoverageIgnore
        }

        // Template file cloning
        if (false === copy($documentTemplate, $this->tempDocumentFilename)) {
            throw new CopyFileException($documentTemplate, $this->tempDocumentFilename); // @codeCoverageIgnore
        }

        // Temporary document content extraction
        $this->zipClass = new ZipArchive();
        $this->zipClass->open($this->tempDocumentFilename);

        $this->tempDocumentMainPart = $this->readPartWithRels($this->getMainPartName());
        $this->tempDocumentSettingsPart = $this->readPartWithRels($this->getSettingsPartName());
        $this->zipClass->locateName($this->getStyleName());
        $this->tempDocumentHeaders = $this->readPartWithRels($this->getStyleName());
        //$this->tempDocumentContentTypes = $this->zipClass->getFromName($this->getDocumentContentTypesName());
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function readPartWithRels($fileName)
    {
        $this->tempDocumentManifest = $this->zipClass->getFromName($this->getManifestName());

        return $this->fixBrokenMacros($this->zipClass->getFromName($fileName));
    }

    /**
     * @param string                                     $search
     * @param \PhpOffice\PhpWord\Element\AbstractElement $complexType
     */
    public function setComplexValue($search, \PhpOffice\PhpWord\Element\AbstractElement $complexType)
    {
        $elementName = substr(get_class($complexType), strrpos(get_class($complexType), '\\') + 1);
        $objectClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Element\\' . $elementName;

        $xmlWriter = new XMLWriter();
        $xmlWriter->setIndent(false);
        /**
         * @var \PhpOffice\PhpWord\Writer\Word2007\Element\AbstractElement $elementWriter
         */
        $elementWriter = new $objectClass($xmlWriter, $complexType, true);
        $elementWriter->write();

        $where = $this->findContainingXmlBlockForMacro($search, 'text:p');
        $block = $this->getSlice($where['start'], $where['end']);
        $textParts = $this->splitTextIntoTexts($block);
        $search = static::ensureMacroCompleted($search);
        $this->replaceXmlBlock($search, $textParts, 'text:p');
        $data = str_replace("\n", '', $xmlWriter->getData());
        $this->replaceXmlBlock($search, $data, 'text:span');
    }

    /**
     * @param string                                     $search
     * @param \PhpOffice\PhpWord\Element\AbstractElement $complexType
     */
    public function setComplexBlock($search, \PhpOffice\PhpWord\Element\AbstractElement $complexType)
    {
        $elementName = substr(get_class($complexType), strrpos(get_class($complexType), '\\') + 1);
        $objectClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Element\\' . $elementName;

        $xmlWriter = new XMLWriter();
        $xmlWriter->setIndent(false);
        /**
         * @var \PhpOffice\PhpWord\Writer\Word2007\Element\AbstractElement $elementWriter
         */
        $elementWriter = new $objectClass($xmlWriter, $complexType, false);
        $elementWriter->write();

        $search = static::ensureMacroCompleted($search);
        $data = str_replace("\n", '', $xmlWriter->getData());
        $this->replaceXmlBlock($search, $data, 'text:p');
    }

    private function addImage($partFileName, $rid, $imgPath, $imageMimeType)
    {
        $extTransform = array(
            'image/jpeg' => 'jpeg',
            'image/png'  => 'png',
            'image/bmp'  => 'bmp',
            'image/gif'  => 'gif',
        );
        $manifestTpl = '<manifest:file-entry manifest:full-path="Pictures/{NAME}" manifest:media-type="{MIME}"/>';

        // get image embed name
        if (isset($this->tempDocumentNewImages[$imgPath])) {
            $imgName = $this->tempDocumentNewImages[$imgPath];
        } else {
            // transform extension
            if (isset($extTransform[$imageMimeType])) {
                $imgExt = $extTransform[$imageMimeType];
            } else {
                throw new Exception("Unsupported image type $imageMimeType");
            }

            // add image to document
            $imgName = 'image_' . $rid . '_' . pathinfo($partFileName, PATHINFO_FILENAME) . '.' . $imgExt;
            $this->zipClass->pclzipAddFile($imgPath, 'Pictures/' . $imgName);
            $this->tempDocumentNewImages[$imgPath] = $imgName;

            // add image to manifest
            $xmlImageRelation = str_replace(array('{NAME}', '{MIME}'), array($imgName, $imageMimeType), $manifestTpl);
            $this->tempDocumentManifest = str_replace('</manifest:manifest>', $xmlImageRelation, $this->tempDocumentManifest) . '</manifest:manifest>';
        }
    }

    /**
     * @param mixed $search
     * @param mixed $replace Path to image, or array("path" => xx, "width" => yy, "height" => zz)
     * @param int   $limit
     */
    public function setImageValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        // prepare $search_replace
        if (!is_array($search)) {
            $search = array($search);
        }

        $replacesList = array();
        if (!is_array($replace) || isset($replace['path'])) {
            $replacesList[] = $replace;
        } else {
            $replacesList = array_values($replace);
        }

        $searchReplace = array();
        foreach ($search as $searchIdx => $searchString) {
            $searchReplace[$searchString] = isset($replacesList[$searchIdx]) ? $replacesList[$searchIdx] : $replacesList[0];
        }

        // collect document parts
        $searchParts = array(
                            $this->getMainPartName() => &$this->tempDocumentMainPart,
                            );
        $searchParts[$this->getStyleName()] = &$this->tempDocumentHeaders;

        // define templates
        $imgTpl = '<text:p><draw:frame draw:name="{Image}" text:anchor-type="char" svg:width="{WIDTH}" svg:height="{HEIGHT}" draw:z-index="0"><draw:image xlink:href="Pictures/{NAME}" xlink:type="simple" xlink:show="embed" xlink:actuate="onLoad" loext:mime-type="{MIME}"/></draw:frame></text:p>';
        $index = 0;

        foreach ($searchParts as $partFileName => &$partContent) {
            $partVariables = $this->getVariablesForPart($partContent);

            foreach ($searchReplace as $searchString => $replaceImage) {
                $varsToReplace = array_filter(
                    $partVariables,
                    function ($partVar) use ($searchString) {
                        return ($partVar == $searchString) || preg_match('/^' . preg_quote($searchString) . ':/', $partVar);
                    }
                );

                foreach ($varsToReplace as $varNameWithArgs) {
                    $varInlineArgs = $this->getImageArgs($varNameWithArgs);
                    $preparedImageAttrs = $this->prepareImageAttrs($replaceImage, $varInlineArgs);
                    $imgPath = $preparedImageAttrs['src'];

                    // get image index
                    //$imgIndex = $this->getNextRelationsIndex($partFileName);
                    $rid = 'rId' . $index; // . $imgIndex;

                    // replace preparations
                    $this->addImage($partFileName, $rid, $imgPath, $preparedImageAttrs['mime']);
                    $index += 1;
                    $name = $this->tempDocumentNewImages[$imgPath];
                    $xmlImage = str_replace(array('{Image}', '{NAME}', '{WIDTH}', '{HEIGHT}', '{MIME}'), array('Image' . $index, $name, $preparedImageAttrs['width'], $preparedImageAttrs['height'], $preparedImageAttrs['mime']), $imgTpl);
                    // replace variable
                    $varNameWithArgsFixed = static::ensureMacroCompleted($varNameWithArgs);
                    $matches = array();
                    if (preg_match('/(<[^<]+>)([^<]*)(' . preg_quote($varNameWithArgsFixed) . ')([^>]*)(<[^>]+>)/Uu', $partContent, $matches)) {
                        $wholeTag = $matches[0];
                        array_shift($matches);
                        list($openTag, $prefix, , $postfix, $closeTag) = $matches;
                        $replaceXml = $openTag . $prefix . $closeTag . $xmlImage . $openTag . $postfix . $closeTag;
                        // replace on each iteration, because in one tag we can have 2+ inline variables => before proceed next variable we need to change $partContent
                        $partContent = $this->setValueForPart($wholeTag, $replaceXml, $partContent, $limit);
                    }
                }
            }
        }
    }

    /**
     * Set a unique value $replace in place of $search.
     *
     * @param mixed $search
     * @param mixed $replace
     * @param int   $limit
     */
    public function setValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (is_array($search)) {
            foreach ($search as &$item) {
                $item = static::ensureMacroCompleted($item);
            }
            unset($item);
        } else {
            $search = static::ensureMacroCompleted($search);
        }

        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = static::ensureUtf8Encoded($item);
            }
            unset($item);
        } else {
            $replace = static::ensureUtf8Encoded($replace);
        }

        if (Settings::isOutputEscapingEnabled()) {
            $xmlEscaper = new Xml();
            $replace = $xmlEscaper->escape($replace);
        }

        $this->tempDocumentHeaders = $this->setValueForPart($search, $replace, $this->tempDocumentHeaders, $limit);
        $this->tempDocumentMainPart = $this->setValueForPart($search, $replace, $this->tempDocumentMainPart, $limit);
        //$this->tempDocumentFooters = $this->setValueForPart($search, $replace, $this->tempDocumentFooters, $limit);
    }

    /**
     * Returns count of all variables in template.
     *
     * @return array
     */
    public function getVariableCount()
    {
        $variables = $this->getVariablesForPart($this->tempDocumentMainPart);
        $variables = array_merge(
            $variables,
            $this->getVariablesForPart($this->tempDocumentHeaders)
        );

        return array_count_values($variables);
    }

    /**
     * Returns array of all variables in template.
     *
     * @return string[]
     */
    public function getVariables()
    {
        return array_keys($this->getVariableCount());
    }

    /**
     * Clone a table row in a template document.
     *
     * @param string $search
     * @param int    $numberOfClones
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function cloneRow($search, $numberOfClones)
    {
        $search = static::ensureMacroCompleted($search);

        $tagPos = strpos($this->tempDocumentMainPart, $search);
        if (!$tagPos) {
            throw new Exception('Can not clone row, template variable not found or variable contains markup.');
        }

        $rowStart = $this->findRowStart($tagPos);
        $rowEnd = $this->findRowEnd($tagPos);
        $xmlRow = $this->getSlice($rowStart, $rowEnd);

        // Check if there's a cell spanning multiple rows.
        if (preg_match('/table:number-rows-spanned="([0-9]+)"/', $xmlRow, $matches)) {
            // $extraRowStart = $rowEnd;
            // Number of spanned rows
            $num = (int) ($matches[1]);
            $extraRowEnd = $rowEnd;
            for ($i = 0; $i < $num - 1; $i++) {
                $extraRowStart = $this->findRowStart($extraRowEnd + 1);
                $extraRowEnd = $this->findRowEnd($extraRowEnd + 1);

                // If extraRowEnd is lower than the end tag, there was no next row found.
                if ($extraRowEnd < strlen('</table:table-row>')) {
                    break;
                }

                //$tmpXmlRow = $this->getSlice($extraRowStart, $extraRowEnd);
                // This row was a spanned row, update $rowEnd and search for the next row.
                $rowEnd = $extraRowEnd;
            }
            $xmlRow = $this->getSlice($rowStart, $rowEnd);
        }
        $result = $this->getSlice(0, $rowStart);
        $result .= implode($this->indexClonedVariables($numberOfClones, $xmlRow));
        $result .= $this->getSlice($rowEnd);
        $this->tempDocumentMainPart = $result;
    }

    /**
     * Clone a block.
     *
     * @param string $blockname
     * @param int    $clones               How many time the block should be cloned
     * @param bool   $replace
     * @param bool   $indexVariables       If true, any variables inside the block will be indexed (postfixed with #1, #2, ...)
     * @param array  $variableReplacements Array containing replacements for macros found inside the block to clone
     *
     * @return string|null
     */
    public function cloneBlock($blockname, $clones = 1, $replace = true, $indexVariables = false, $variableReplacements = null)
    {
        $xmlBlock = null;
        $matches = array();
        preg_match(
            '/(<\?xml.*)(<[^<>]+>\${' . $blockname . '}<\/[^<>]+>)(.*)(<[^<>]+>\${\/' . $blockname . '}<\/[^<>]+>)/is',
            $this->tempDocumentMainPart,
            $matches
        );

        if (isset($matches[3])) {
            $xmlBlock = $matches[3];
            if ($indexVariables) {
                $cloned = $this->indexClonedVariables($clones, $xmlBlock);
            } elseif ($variableReplacements !== null && is_array($variableReplacements)) {
                $cloned = $this->replaceClonedVariables($variableReplacements, $xmlBlock);
            } else {
                $cloned = array();
                for ($i = 1; $i <= $clones; $i++) {
                    $cloned[] = $xmlBlock;
                }
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
     */
    public function replaceBlock($blockname, $replacement)
    {
        $matches = array();
        preg_match(
            '/(<\?xml.*)(<[^<>]+>\${' . $blockname . '}<\/[^<>]+>)(.*)(<[^<>]+>\${\/' . $blockname . '}<\/[^<>]+>)/is',
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
     * Automatically Recalculate Fields on Open
     *
     * @param bool $update
     */
    public function setUpdateFields($update = true)
    {
        return 'Not implemented';
    }

    /**
     * Saves the result document.
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return string
     */
    public function save()
    {
        $this->savePart($this->getMainPartName(), $this->tempDocumentMainPart);
        if (strlen($this->tempDocumentSettingsPart) != 0) {
            $this->savePart($this->getSettingsPartName(), $this->tempDocumentSettingsPart);
        }
        $this->savePart($this->getStyleName(), $this->tempDocumentHeaders);
        $this->savePart($this->getManifestName(), $this->tempDocumentManifest);

        // Close zip file
        if (false === $this->zipClass->close()) {
            throw new Exception('Could not close zip file.'); // @codeCoverageIgnore
        }

        return $this->tempDocumentFilename;
    }

    /**
     * @param string $fileName
     * @param string $xml
     */
    protected function savePart($fileName, $xml)
    {
        $this->zipClass->addFromString($fileName, $xml);
    }

    /**
     * @return string
     */
    protected function getMainPartName()
    {
        return  'content.xml';
    }

    /**
     * @return string
     */
    protected function getManifestName()
    {
        return  'META-INF/manifest.xml';
    }

    /**
     * The name of the file containing the Settings part
     *
     * @return string
     */
    protected function getSettingsPartName()
    {
        return 'settings.xml';
    }

    /**
     * The name of the file containing the headr and footer
     *
     * @return string
     */
    protected function getStyleName()
    {
        return 'styles.xml';
    }

    /**
     * Find the start position of the nearest table row before $offset.
     *
     * @param int $offset
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return int
     */
    protected function findRowStart($offset)
    {
        $rowStart = strrpos($this->tempDocumentMainPart, '<table:table-row ', ((strlen($this->tempDocumentMainPart) - $offset) * -1));

        if (!$rowStart) {
            $rowStart = strrpos($this->tempDocumentMainPart, '<table:table-row>', ((strlen($this->tempDocumentMainPart) - $offset) * -1));
        }
        if (!$rowStart) {
            throw new Exception('Can not find the start position of the row to clone.');
        }

        return $rowStart;
    }

    /**
     * Find the end position of the nearest table row after $offset.
     *
     * @param int $offset
     *
     * @return int
     */
    protected function findRowEnd($offset)
    {
        $endMarkup = '</table:table-row>';
        $position = strpos($this->tempDocumentMainPart, $endMarkup, $offset);
        if (!$position) {
            throw new Exception('End of text before end of row.');
        }

        return $position + strlen($endMarkup);
    }

    /**
     * Splits a w:r/w:t into a list of w:r where each ${macro} is in a separate w:r
     *
     * @param  string $text
     * @return string
     */
    protected function splitTextIntoTexts($text)
    {
        if (!$this->textNeedsSplitting($text)) {
            return $text;
        }
        $matches = array();
        if (preg_match('/([^<>]*)(\s[^<>]+)>/', $text, $matches)) {
            $extractedTag = $matches[1];
            $extractedStyle = $matches[2];
        } else {
            $extractedStyle = '';
            preg_match('/([^<>]*)>/', $text, $matches);
            $extractedTag = $matches[1];
        }

        // Text between tags
        preg_match('/[^<>]*>(.*)<\//', $text, $matches);
        $cdata = $matches[1];
        $remainingText = $cdata;
        preg_match_all('/(\${[^}]*})/', $cdata, $matches);
        if ($extractedTag == 'text:p') {
            $result = '<' . $extractedTag . $extractedStyle . '>';
            foreach ($matches[1] as $macro) {
                $beforeText = stristr($remainingText, $macro, $before_needle = true);
                if (strlen($beforeText) != 0) {
                    $result .= '<text:span>' . $beforeText . '</text:span>';
                    $result .= '<text:span>' . $macro . '</text:span>';
                }
                $remainingText = substr($remainingText, strlen($beforeText) + strlen($macro));
            }
            if ($remainingText != '') {
                $result .= '<text:span>' . $remainingText . '</text:span>';
            }
            $result .= '</' . $extractedTag . '>';
        } else {
            $result = '';
            foreach ($matches[1] as $macro) {
                $beforeText = stristr($remainingText, $macro, $before_needle = true);
                if (strlen($beforeText) != 0) {
                    $result .= '<text:span' . $extractedStyle . '>' . $beforeText . '</text:span>';
                }
                $result .= '<text:span' . $extractedStyle . '>' . $macro . '</text:span>';
                $remainingText = substr($remainingText, strlen($beforeText) + strlen($macro));
            }
            if ($remainingText != '') {
                $result .= '<text:span' . $extractedStyle . '>' . $remainingText . '</text:span>';
            }
        }

        return  $result;
    }
}
