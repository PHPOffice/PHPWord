<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Custom properties reader
 */
class DocPropsCustom extends AbstractPart
{
    /**
     * Read custom document properties
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
                $propertyName = $xmlReader->getAttribute('name', $node);
                $attributeNode = $xmlReader->getElement('*', $node);
                $attributeType = $attributeNode->nodeName;
                $attributeValue = $attributeNode->nodeValue;
                $attributeValue = DocumentProperties::convertProperty($attributeValue, $attributeType);
                $attributeType = DocumentProperties::convertPropertyType($attributeType);
                $docProps->setCustomProperty($propertyName, $attributeValue, $attributeType);
            }
        }
    }
}
