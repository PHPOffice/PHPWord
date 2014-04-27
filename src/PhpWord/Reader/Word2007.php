<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Reader for Word2007
 *
 * @since 0.8.0
 * @todo watermark, checkbox, toc
 * @todo Partly done: image, object
 */
class Word2007 extends AbstractReader implements ReaderInterface
{
    /**
     * Loads PhpWord from file
     *
     * @param string $docFile
     * @return \PhpOffice\PhpWord\PhpWord
     */
    public function load($docFile)
    {
        $phpWord = new PhpWord();
        $relationships = $this->readRelationships($docFile);

        $steps = array(
            array('stepPart' => 'document', 'stepItems' => array(
                'styles'    => 'Styles',
                'numbering' => 'Numbering',
            )),
            array('stepPart' => 'main', 'stepItems' => array(
                'officeDocument'      => 'Document',
                'core-properties'     => 'DocPropsCore',
                'extended-properties' => 'DocPropsApp',
                'custom-properties'   => 'DocPropsCustom',
            )),
            array('stepPart' => 'document', 'stepItems' => array(
                'endnotes'  => 'Endnotes',
                'footnotes' => 'Footnotes',
            )),
        );

        foreach ($steps as $step) {
            $stepPart = $step['stepPart'];
            $stepItems = $step['stepItems'];
            foreach ($relationships[$stepPart] as $relItem) {
                $relType = $relItem['type'];
                if (array_key_exists($relType, $stepItems)) {
                    $partName = $stepItems[$relType];
                    $xmlFile = $relItem['target'];
                    $this->readPart($phpWord, $relationships, $partName, $docFile, $xmlFile);
                }
            }
        }

        return $phpWord;
    }

    /**
     * Read document part
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @param array $relationships
     * @param string $partName
     * @param string $docFile
     * @param string $xmlFile
     */
    private function readPart(PhpWord &$phpWord, $relationships, $partName, $docFile, $xmlFile)
    {
        $partClass = "PhpOffice\\PhpWord\\Reader\\Word2007\\{$partName}";
        if (class_exists($partClass)) {
            $part = new $partClass($docFile, $xmlFile);
            $part->setRels($relationships);
            $part->read($phpWord);
        }

    }

    /**
     * Read all relationship files
     *
     * @param string $docFile
     * @return array
     */
    private function readRelationships($docFile)
    {
        $relationships = array();

        // _rels/.rels
        $relationships['main'] = $this->getRels($docFile, '_rels/.rels');

        // word/_rels/*.xml.rels
        $wordRelsPath = 'word/_rels/';
        $zipClass = Settings::getZipClass();
        $zip = new $zipClass();
        if ($zip->open($docFile) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $xmlFile = $zip->getNameIndex($i);
                if ((substr($xmlFile, 0, strlen($wordRelsPath))) == $wordRelsPath && (substr($xmlFile, -1)) != '/') {
                    $docPart = str_replace('.xml.rels', '', str_replace($wordRelsPath, '', $xmlFile));
                    $relationships[$docPart] = $this->getRels($docFile, $xmlFile, 'word/');
                }
            }
            $zip->close();
        }

        return $relationships;
    }

    /**
     * Get relationship array
     *
     * @param string $docFile
     * @param string $xmlFile
     * @param string $targetPrefix
     * @return array
     */
    private function getRels($docFile, $xmlFile, $targetPrefix = '')
    {
        $metaPrefix = 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/';
        $officePrefix = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';

        $rels = array();

        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($docFile, $xmlFile);
        $nodes = $xmlReader->getElements('*');
        foreach ($nodes as $node) {
            $rId = $xmlReader->getAttribute('Id', $node);
            $type = $xmlReader->getAttribute('Type', $node);
            $target = $xmlReader->getAttribute('Target', $node);

            // Remove URL prefixes from $type to make it easier to read
            $type = str_replace($metaPrefix, '', $type);
            $type = str_replace($officePrefix, '', $type);
            $docPart = str_replace('.xml', '', $target);

            // Do not add prefix to link source
            if (!in_array($type, array('hyperlink'))) {
                $target = $targetPrefix . $target;
            }

            // Push to return array
            $rels[$rId] = array('type' => $type, 'target' => $target, 'docPart' => $docPart);
        }
        ksort($rels);

        return $rels;
    }
}
