<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Core properties reader
 */
class DocPropsCore extends AbstractPart
{
    /**
     * Property mapping
     *
     * @var array
     */
    protected $mapping = array(
        'dc:creator' => 'setCreator',
        'dc:title' => 'setTitle',
        'dc:description' => 'setDescription',
        'dc:subject' => 'setSubject',
        'cp:keywords' => 'setKeywords',
        'cp:category' => 'setCategory',
        'cp:lastModifiedBy' => 'setLastModifiedBy',
        'dcterms:created' => 'setCreated',
        'dcterms:modified' => 'setModified',
    );

    /**
     * Callback functions
     *
     * @var array
     */
    protected $callbacks = array('dcterms:created' => 'strtotime', 'dcterms:modified' => 'strtotime');

    /**
     * Read core/extended document properties
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(PhpWord &$phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $docProps = $phpWord->getDocumentProperties();

        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                if (!array_key_exists($node->nodeName, $this->mapping)) {
                    continue;
                }
                $method = $this->mapping[$node->nodeName];
                $value = $node->nodeValue == '' ? null : $node->nodeValue;
                if (array_key_exists($node->nodeName, $this->callbacks)) {
                    $value = $this->callbacks[$node->nodeName]($value);
                }
                if (method_exists($docProps, $method)) {
                    $docProps->$method($value);
                }
            }
        }
    }
}
