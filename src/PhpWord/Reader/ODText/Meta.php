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

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\Common\XMLReader;
use PhpOffice\PhpWord\PhpWord;

/**
 * Meta reader
 *
 * @since 0.11.0
 */
class Meta extends AbstractPart
{
    /**
     * Read meta.xml.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @todo Process property type
     */
    public function read(PhpWord $phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);
        $docProps = $phpWord->getDocInfo();

        $metaNode = $xmlReader->getElement('office:meta');

        // Standard properties
        $properties = array(
            'title'          => 'dc:title',
            'subject'        => 'dc:subject',
            'description'    => 'dc:description',
            'keywords'       => 'meta:keyword',
            'creator'        => 'meta:initial-creator',
            'lastModifiedBy' => 'dc:creator',
            // 'created'        => 'meta:creation-date',
            // 'modified'       => 'dc:date',
        );
        foreach ($properties as $property => $path) {
            $method = "set{$property}";
            $propertyNode = $xmlReader->getElement($path, $metaNode);
            if ($propertyNode !== null && method_exists($docProps, $method)) {
                $docProps->$method($propertyNode->nodeValue);
            }
        }

        // Custom properties
        $propertyNodes = $xmlReader->getElements('meta:user-defined', $metaNode);
        foreach ($propertyNodes as $propertyNode) {
            $property = $xmlReader->getAttribute('meta:name', $propertyNode);

            // Set category, company, and manager property
            if (in_array($property, array('Category', 'Company', 'Manager'))) {
                $method = "set{$property}";
                $docProps->$method($propertyNode->nodeValue);
            } else {
                // Set other custom properties
                $docProps->setCustomProperty($property, $propertyNode->nodeValue);
            }
        }
    }
}
