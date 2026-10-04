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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Footnotes reader.
 *
 * @since 0.10.0
 */
class Footnotes extends AbstractPart
{
    /**
     * Collection name footnotes|endnotes.
     *
     * @var string
     */
    protected $collection = 'footnotes';

    /**
     * Element name footnote|endnote.
     *
     * @var string
     */
    protected $element = 'footnote';

    /**
     * Read (footnotes|endnotes).xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);
        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $id = $xmlReader->getAttribute('w:id', $node);
                $type = $xmlReader->getAttribute('w:type', $node);

                // Avoid w:type "separator" and "continuationSeparator"
                // Only look for <footnote> or <endnote> without w:type attribute, or with w:type = normal
                if ((null === $type || $type === 'normal')) {
                    $element = $this->getElement($phpWord, $id);
                    if ($element !== null) {
                        $pNodes = $xmlReader->getElements('w:p', $node);
                        $isFirstParagraph = true;
                        foreach ($pNodes as $pNode) {
                            if ($isFirstParagraph && $xmlReader->elementExists('w:pPr', $pNode) && method_exists($element, 'setParagraphStyle')) {
                                $element->setParagraphStyle($this->readParagraphStyle($xmlReader, $pNode));
                            }
                            $isFirstParagraph = false;

                            foreach ($xmlReader->getElements('*', $pNode) as $runNode) {
                                $this->readRun($xmlReader, $runNode, $element, $this->collection);
                            }
                        }
                        $addMethod = "add{$this->element}";
                        $phpWord->$addMethod($element);
                    }
                }
            }
        }
    }

    /**
     * Searches for the element with the given relationId.
     *
     * @param int $relationId
     *
     * @return null|\PhpOffice\PhpWord\Element\AbstractContainer
     */
    private function getElement(PhpWord $phpWord, $relationId)
    {
        $getMethod = "get{$this->collection}";
        $collection = $phpWord->$getMethod()->getItems();

        //not found by key, looping to search by relationId
        foreach ($collection as $collectionElement) {
            if ($collectionElement->getRelationId() == $relationId) {
                return $collectionElement;
            }
        }

        return null;
    }
}
