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
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Reader for ODText
 *
 * @since 0.10.0
 */
class ODText extends AbstractReader implements ReaderInterface
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

        $readerParts = array(
            'content.xml' => 'Content',
        );

        foreach ($readerParts as $xmlFile => $partName) {
            $this->readPart($phpWord, $relationships, $partName, $docFile, $xmlFile);
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
        $partClass = "PhpOffice\\PhpWord\\Reader\\ODText\\{$partName}";
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
        $rels = array();
        $xmlFile = 'META-INF/manifest.xml';
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($docFile, $xmlFile);
        $nodes = $xmlReader->getElements('manifest:file-entry');
        foreach ($nodes as $node) {
            $type = $xmlReader->getAttribute('manifest:media-type', $node);
            $target = $xmlReader->getAttribute('manifest:full-path', $node);
            $rels[] = array('type' => $type, 'target' => $target);
        }

        return $rels;
    }
}
