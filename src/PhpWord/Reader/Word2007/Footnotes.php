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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\Common\XMLReader;
use PhpOffice\PhpWord\PhpWord;

/**
 * Footnotes reader
 *
 * @since 0.10.0
 */
class Footnotes extends AbstractPart
{
    /**
     * Collection name footnotes|endnotes
     *
     * @var string
     */
    protected $collection = 'footnotes';

    /**
     * Element name footnote|endnote
     *
     * @var string
     */
    protected $element = 'footnote';

    /**
     * Read (footnotes|endnotes).xml.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(PhpWord $phpWord)
    {
        $getMethod = "get{$this->collection}";
        $collection = $phpWord->$getMethod()->getItems();

        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);
        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $id = $xmlReader->getAttribute('w:id', $node);
                $type = $xmlReader->getAttribute('w:type', $node);

                // Avoid w:type "separator" and "continuationSeparator"
                // Only look for <footnote> or <endnote> without w:type attribute
                if (is_null($type) && isset($collection[$id])) {
                    $element = $collection[$id];
                    $pNodes = $xmlReader->getElements('w:p/*', $node);
                    foreach ($pNodes as $pNode) {
                        $this->readRun($xmlReader, $pNode, $element, $this->collection);
                    }
                    $addMethod = "add{$this->element}";
                    $phpWord->$addMethod($element);
                }
            }
        }
    }
}
