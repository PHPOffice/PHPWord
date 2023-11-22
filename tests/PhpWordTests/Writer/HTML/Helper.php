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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use DOMDocument;
use DOMXPath;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style subnamespace.
 */
class Helper extends \PHPUnit\Framework\TestCase
{
    public static function getTextContent(DOMXPath $xpath, string $query, string $namedItem = '', int $itemNumber = 0): string
    {
        $returnVal = '';
        $item = $xpath->query($query);
        if ($item === false) {
            self::fail('Unexpected false return from xpath query');
        } else {
            $item2 = $item->item($itemNumber);
            if ($item2 === null) {
                self::fail('Unexpected null return requesting item');
            } elseif ($namedItem !== '') {
                $item3 = $item2->attributes->getNamedItem($namedItem);
                if ($item3 === null) {
                    self::fail('Unexpected null return requesting namedItem');
                } else {
                    $returnVal = $item3->textContent;
                }
            } else {
                $returnVal = $item2->textContent;
            }
        }

        return $returnVal;
    }

    /** @return mixed */
    public static function getNamedItem(DOMXPath $xpath, string $query, string $namedItem, int $itemNumber = 0)
    {
        $returnVal = '';
        $item = $xpath->query($query);
        if ($item === false) {
            self::fail('Unexpected false return from xpath query');
        } else {
            $item2 = $item->item($itemNumber);
            if ($item2 === null) {
                self::fail('Unexpected null return requesting item');
            } else {
                $returnValue = $item2->attributes->getNamedItem($namedItem);
            }
        }

        return $returnVal;
    }

    public static function getLength(DOMXPath $xpath, string $query): int
    {
        $returnVal = 0;
        $item = $xpath->query($query);
        if ($item === false) {
            self::fail('Unexpected false return from xpath query');
        } else {
            $returnVal = $item->length;
        }

        return $returnVal;
    }

    public static function getAsHTML(PhpWord $phpWord, string $defaultWhiteSpace = '', string $defaultGenericFont = ''): DOMDocument
    {
        $htmlWriter = new HTML($phpWord);
        $htmlWriter->setDefaultWhiteSpace($defaultWhiteSpace);
        $htmlWriter->setDefaultGenericFont($defaultGenericFont);
        $dom = new DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }
}
