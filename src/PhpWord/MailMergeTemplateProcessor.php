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

use FG\ASN1\TemplateParser;
use PhpOffice\PhpWord\Escaper\RegExp;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Zend\Stdlib\StringUtils;

class MailMergeTemplateProcessor extends TemplateProcessor
{
    /**
     * Merge field replacement reporting information
     */
    private $temporarySectionName;
    private $mergeData = array();
    private $mergeSuccess = array();
    private $mergeFailure = array();

    /**
     * Get all mail merge variables from document
     *
     */
    public function getMailMergeVariables()
    {
        // only care about body
        $variables = $this->getMailMergeVariablesForPart($this->tempDocumentMainPart);
        return array_unique($variables);
    }

    protected function getMailMergeVariablesForPart($documentPartXML)
    {
        preg_match_all('/(?:<w:instrText.+?\s+MERGEFIELD\s+\"*)(\w+)(?:\"*\s+<\/w:instrText>)/uim', $documentPartXML, $matches);
        return $this->parseMergeSection([$matches[1]]);
    }

    public function setMailMergeValue($find, $replace) {

    }

    public function setMergeData($data) {
        $this->mergeData = $data;
    }

    public function getMergeSuccess() {
        return $this->mergeSuccess;
    }
    public function getMergeFailure() {
        return $this->mergeFailure;
    }

    public function doMerge() {
        $this->mergeData = array_change_key_case($this->mergeData, CASE_UPPER);

        foreach ($this->tempDocumentHeaders as $index => $headerXML) {
            $this->temporarySectionName = 'header'.$index;
            $this->tempDocumentHeaders[$index] = $this->doMergeForPart($this->tempDocumentHeaders[$index]);
        }

        $this->temporarySectionName = 'document';
        $this->tempDocumentMainPart = $this->doMergeForPart($this->tempDocumentMainPart);
        foreach ($this->tempDocumentFooters as $index => $headerXML) {
            $this->temporarySectionName = 'footer'.$index;
            $this->tempDocumentFooters[$index] = $this->doMergeForPart($this->tempDocumentFooters[$index]);
        }
    }

    /**
     * Find and replace merge fields in the given XML section.
     * @return string
     */
    protected function doMergeForPart($documentPartXML) {
        // break down major sections <w:p>
        return preg_replace_callback("/<w:p[\s>].+?<\/w:p>/si", array($this, 'parseMergeSection'),
            $documentPartXML);
    }

    protected function parseMergeSection($replace) {
        $section = $replace[0];
        $section = preg_replace('/<\/w:instrText><\/w:r><w:r\s+w:rsidR="\w+"><w:instrText\s+xml:space="preserve">/uim',
            '', $section);
        $a =1; // TODO REMOVE

        return preg_replace_callback(
            "/(<w:r[\s>]((?!<\/w:r>).)*?<w:fldChar\s+w:fldCharType=\"begin\"\/>.*?<\/w:r>)\s*(<w:r[\s>].+?\s+MERGEFIELD\s+\"*\w+\"*\s+.+?<\/w:r>)\s*(<w:r[\s>].*?<w:fldChar\s+w:fldCharType=\"separate\"\/>.*?<\/w:r>)\s*(<w:r[\s>].+?<\/w:r>)\s*(<w:r[\s>].*?<w:fldChar\s+w:fldCharType=\"end\"\/>.*?<\/w:r>)/uim",
            array($this, 'parseMergeReplace'), $section);
    }

    protected function parseMergeReplace($replace) {
        /* $replace: array 1..x corresponds to () matches in preg_replace */
        $field = $replace[3];
        $final = $replace[5];

        if (preg_match('/(?:<w:instrText.+?\s+MERGEFIELD\s+\"*)(\w+)(?:\"*\s+<\/w:instrText>)/sui', $field, $match)) {
            $key = strtoupper($match[1]);
        } else {
            return $replace[0];
        }

        $sec = $this->temporarySectionName.'/'.$key;

        if (isset($this->mergeData[$key])) {
            /* success */
            $newval = $this->mergeData[$key];
            $this->mergeSuccess[$sec] = (isset($this->mergeSuccess[$sec])?$this->mergeSuccess[$sec]:0) + 1;
        } else {
            /* failure */
            $newval = $key;
            $this->mergeFailure[$sec] = (isset($this->mergeFailure[$sec])?$this->mergeFailure[$sec]:0) + 1;
        }

        $final = preg_replace('/(<w:t.*?>)(.+?)(<\/w:t>)/si', '${1}'.$newval.'${3}', $final);
        return $final;
    }

}