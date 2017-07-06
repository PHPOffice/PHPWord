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

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\Common\XMLReader;
use PhpOffice\PhpWord\PhpWord;

/**
 * Settings reader
 *
 * @since 0.14.0
 */
class Settings extends AbstractPart
{

    private static $booleanProperties = array('hideSpellingErrors', 'hideGrammaticalErrors', 'evenAndOddHeaders');

    /**
     * Read settings.xml.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return void
     */
    public function read(PhpWord $phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $docSettings = $phpWord->getSettings();

        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $name = str_replace('w:', '', $node->nodeName);
                $value = $xmlReader->getAttribute('w:val', $node);
                $method = 'set' . $name;

                if (in_array($name, $this::$booleanProperties)) {
                    if ($value == 'false') {
                        $docSettings->$method(false);
                    } else {
                        $docSettings->$method(true);
                    }
                } else if (method_exists($this, $method)) {
                    $this->$method($xmlReader, $phpWord, $node);
                } else if (method_exists($this, $method)) {
                    $docSettings->$method($value);
                }
            }
        }
    }

    /**
     * Sets the document protection
     * 
     * @param XMLReader $xmlReader
     * @param PhpWord $phpWord
     * @param \DOMNode $node
     */
    protected function setDocumentProtection(XMLReader $xmlReader, PhpWord $phpWord, \DOMNode $node) {
        $documentProtection = $phpWord->getSettings()->getDocumentProtection();

        $edit = $xmlReader->getAttribute('w:edit', $node);
        $documentProtection->setEditing($edit);
    }
}
